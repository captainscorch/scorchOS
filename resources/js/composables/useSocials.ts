export interface Social {
    label: string;
    url: string;
}

export function useSocials(): Social[] {
    return [
        { label: 'IG', url: 'https://www.instagram.com/captainscorch' },
        { label: '&#120143;', url: 'https://x.com/captainscorch' },
        { label: 'LinkedIn', url: 'https://www.linkedin.com/in/captainscorch' },
        { label: 'GitHub', url: 'https://github.com/captainscorch' },
        { label: 'Email', url: 'mailto:hi@captainscor.ch' },
    ];
}
