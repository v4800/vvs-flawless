import { router } from '@inertiajs/vue3';
import type { FlashToast } from '@/types/ui';

export function initializeFlashToast(): void {
    router.on('flash', async (event) => {
        const flash = (event as CustomEvent).detail?.flash;
        const data = flash?.toast as FlashToast | undefined;

        if (!data) {
            return;
        }

        const { toast } = await import('vue-sonner');

        toast[data.type](data.message);
    });
}
