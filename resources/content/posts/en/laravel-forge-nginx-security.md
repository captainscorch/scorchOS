---
slug: laravel-forge-nginx-security
title: 'Enhanced Security for Laravel Forge nginx Configurations'
date: '2026-01-28'
category: ['Craft']
tags: ['laravel', 'forge', 'nginx', 'security', 'server-configuration', 'devops']
excerpt: "Enhancements I've made to the default Laravel Forge nginx configuration: additional security headers, file access restrictions, and production-ready improvements."
---

The default nginx config from Laravel Forge is fundamentally solid, but I've built in a few enhancements to improve my server setups.

## Security Headers

By default, Laravel Forge's nginx configuration sets a few core security headers:

```nginx
add_header X-Frame-Options "SAMEORIGIN";
add_header X-XSS-Protection "1; mode=block";
add_header X-Content-Type-Options "nosniff";
```

These provide a solid foundation, but they only apply to successful (2xx, 3xx) responses. To improve this, I apply the `always` directive, ensuring they're included with every response—even error pages. I also add a couple of modern headers for better security:

```nginx
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
```

**The additions:**

- `always` directive ensures headers are sent even on error responses
- `Referrer-Policy` determines what information about the user's originating page (the "referrer") is passed along when navigating away from your site. Setting it to `strict-origin-when-cross-origin` helps prevent sensitive site URLs or data from being leaked to external domains.
- `Strict-Transport-Security` (HSTS) enforces HTTPS connections (only if SSL is configured) to prevent man-in-the-middle attacks

## File Access Restrictions

### Protect Sensitive Files

I block access to sensitive configuration and backup files to ensure they are not exposed to the public if placed in the wrong directory by mistake:

```nginx
location ~* \.(env|log|sql|bak|old|orig|config|ini|sh)$ {
    deny all;
}
```

### Block Dependency Files

I also block access to dependency management files for the same reason: these files can reveal information about application dependencies:

```nginx
location ~* (composer\.(json|lock)|package(-lock)?\.json|yarn\.lock|\.npmrc)$ {
    deny all;
}
```

### Protect Storage Directory

For Laravel applications, I secure the storage directory like this:

```nginx
location ^~ /storage/ {
    try_files $uri =404;

    location ~ \.php$ {
        return 403;
    }
}
```

This ensures only static files from storage are served and PHP files can't be executed directly. This is very helpful to prevent security vulnerabilities if malicious files are placed in the storage directory through a malicious package or vulnerabilities in the file upload process when working with user-based file uploads.

## Common Attack Vectors

Since I'm not using WordPress, I block common WordPress paths to prevent automated attacks:

```nginx
location ~* ^/(wp-admin|wp-login|wp-content|wp-includes|wp-json|wp-admin/install\.php|phpmyadmin|pma|myadmin|admin\.php|xmlrpc\.php|cgi-bin) {
    return 444;
}
```

Status code `444` closes the connection without response, which is more efficient than returning an error page for automated scanners.

## PHP Configuration Enhancements

### Hide Server Information

I remove the `X-Powered-By` header to reduce information disclosure:

```nginx
location ~ \.php$ {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_hide_header X-Powered-By;
}
```

Update the PHP version (`php8.4-fpm.sock`) to match your server's PHP version.

## Static Asset Caching

I optimize caching for build assets:

```nginx
location ^~ /build/ {
    expires 1y;
    add_header Cache-Control "public, max-age=31536000, immutable";
    access_log off;
    log_not_found off;
    try_files $uri =404;
}
```

This sets aggressive caching for versioned build assets, improving performance while reducing server load.

## Notes

1. **HSTS:** Only enable `Strict-Transport-Security` if you have SSL/TLS properly configured. Once set, browsers will enforce HTTPS for the specified duration.

2. **Testing:** After making changes, test with `nginx -t` and reload with `sudo service nginx reload` or through Forge's interface.

3. **Backup:** Always backup your current configuration before making changes.

These tweaks add defense-in-depth: less information leakage, common attack paths closed, sensitive files locked down. The nginx layer is only one part of it though—keeping application code, dependencies, and the stack itself up to date is where the real work lives.
