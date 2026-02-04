---
slug: laravel-forge-nginx-security
title: 'Erweiterte Sicherheit für Laravel Forge nginx-Konfigurationen'
date: '2026-01-28'
category: ['Craft']
tags: ['laravel', 'forge', 'nginx', 'sicherheit', 'server-konfiguration', 'devops']
excerpt: 'Erweiterungen, die ich an der Standard Laravel Forge nginx-Konfiguration vorgenommen habe: zusätzliche Security-Header, Dateizugriffsbeschränkungen und production-ready Verbesserungen.'
---

Die Standard-nginx-Config von Laravel Forge ist grundsätzlich solide, aber ich habe ein paar Erweiterungen eingebaut, um meine
Server-Setups zu verbessern.

## Security Header

Standardmäßig setzt die Laravel Forge nginx-Konfiguration einige zentrale Security-Header:

```nginx
add_header X-Frame-Options "SAMEORIGIN";
add_header X-XSS-Protection "1; mode=block";
add_header X-Content-Type-Options "nosniff";
```

Das ist bereits eine gute Basis, aber diese Header gelten nur bei erfolgreichen Antworten (2xx, 3xx). Um das zu verbessern, verwende ich die Direktive `always`, damit sie bei jeder Antwort gesendet werden – auch bei Fehlerseiten. Außerdem ergänze ich ein paar moderne Header für mehr Sicherheit:

```nginx
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
```

**Die Ergänzungen:**

- Die Direktive `always` stellt sicher, dass die Header auch bei Fehlerantworten gesendet werden
- `Referrer-Policy` legt fest, welche Informationen über die Ursprungsseite (den „Referrer“) beim Verlassen deiner Website übertragen werden. Mit der Einstellung `strict-origin-when-cross-origin` wird verhindert, dass sensible URLs oder Daten an externe Domains gelangen.
- `Strict-Transport-Security` (HSTS) erzwingt HTTPS-Verbindungen (nur bei konfiguriertem SSL) um Man-in-the-Middle-Angriffe zu vermeiden

## Dateizugriffsbeschränkungen

### Sensible Dateien schützen

Ich blockiere den Zugriff auf sensible Konfigurations- und Backup-Dateien, damit sie nicht öffentlich erreichbar sind, wenn sie versehentlich im falschen Verzeichnis liegen:

```nginx
location ~* \.(env|log|sql|bak|old|orig|config|ini|sh)$ {
    deny all;
}
```

### Dependency-Dateien blockieren

Aus dem gleichen Grund blockiere ich den Zugriff auf Dependency-Management Dateien, da diese Informationen über Anwendungsdependencies preisgeben können:

```nginx
location ~* (composer\.(json|lock)|package(-lock)?\.json|yarn\.lock|\.npmrc)$ {
    deny all;
}
```

### Storage-Verzeichnis schützen

Bei Laravel-Anwendungen sichere ich das Storage-Verzeichnis so:

```nginx
location ^~ /storage/ {
    try_files $uri =404;

    location ~ \.php$ {
        return 403;
    }
}
```

Damit werden nur statische Dateien aus dem Storage ausgeliefert und PHP-Dateien können nicht direkt ausgeführt werden. Das ist sehr hilfreich, um Sicherheitslücken zu vermeiden, wenn z. B. über schädliche Pakete oder Schwachstellen beim Datei-Upload Schadcode ins Storage-Verzeichnis gelangt.

## Häufige Angriffsvektoren

Da ich kein WordPress verwende, blockiere ich typische WordPress-Pfade, um automatisierte Angriffe abzuwehren:

```nginx
location ~* ^/(wp-admin|wp-login|wp-content|wp-includes|wp-json|wp-admin/install\.php|phpmyadmin|pma|myadmin|admin\.php|xmlrpc\.php|cgi-bin) {
    return 444;
}
```

Der Statuscode `444` beendet die Verbindung ohne Antwort – für automatisierte Scanner effizienter als eine Fehlerseite.

## PHP-Konfigurationsverbesserungen

### Server-Informationen verbergen

Ich entferne den Header `X-Powered-By`, um weniger Informationen preiszugeben:

```nginx
location ~ \.php$ {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_hide_header X-Powered-By;
}
```

Pass die PHP-Version (`php8.4-fpm.sock`) an die Version auf deinem Server an.

## Caching statischer Assets

Ich optimiere das Caching für Build-Assets:

```nginx
location ^~ /build/ {
    expires 1y;
    add_header Cache-Control "public, max-age=31536000, immutable";
    access_log off;
    log_not_found off;
    try_files $uri =404;
}
```

Damit wird für versionierte Build-Assets aggressiv gecacht, was die Performance verbessert und die Serverlast reduziert.

## Hinweise

1. **HSTS:** Aktiviere `Strict-Transport-Security` nur, wenn SSL/TLS korrekt konfiguriert ist. Einmal gesetzt, erzwingen Browser für die angegebene Dauer HTTPS.

2. **Testing:** Nach Änderungen mit `nginx -t` testen und mit `sudo service nginx reload` oder über die Forge-Oberfläche neu laden.

3. **Backup:** Vor Änderungen immer ein Backup der aktuellen Konfiguration anlegen.

Die Anpassungen sorgen für Defense-in-Depth: weniger Informationspreisgabe, typische Angriffswege geschlossen, sensible Dateien abgeschirmt. Die nginx-Ebene ist nur ein Teil davon – der eigentliche Aufwand liegt darin, Anwendungscode, Dependencies und den Stack selbst aktuell zu halten.
