declare module 'fslightbox-vue' {
    import { DefineComponent } from 'vue';

    interface FsLightboxProps {
        toggler?: boolean;
        sources?: string[];
        types?: Array<'image' | 'video' | 'youtube'>;
        slide?: number;
        disableBackgroundClose?: boolean;
        [key: string]: unknown;
    }

    const FsLightbox: DefineComponent<FsLightboxProps>;
    export default FsLightbox;
}
