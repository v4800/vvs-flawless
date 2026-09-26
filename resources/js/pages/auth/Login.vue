<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

import { store } from '@/routes/login';
import { request } from '@/routes/password';

import PasskeyVerify from '@/components/PasskeyVerify.vue';

defineOptions({
    layout: {
        title: 'Connexion administrateur',
        description: 'Accès réservé à VVS FLAWLESS',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const page = usePage();
const copy = computed(() => page.props.translations.auth);
</script>

<template>
    <Head :title="copy.login_title" />

    <div
        v-if="status"
        role="status"
        class="mb-5 rounded-xl border border-emerald-400/20 bg-emerald-400/[0.07] px-4 py-3 text-sm leading-6 text-emerald-200"
    >
        {{ status }}
    </div>

    <PasskeyVerify
         :label="copy.passkey"
         :loading-label="copy.passkey_loading"
         :separator="copy.separator"
    />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-5">
            <div class="grid gap-2.5">
                <Label
                    for="email"
                    class="text-[0.7rem] font-semibold uppercase tracking-[0.12em] text-[#c9c2b7]"
                >
                    {{ copy.email }}
                </Label>

                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="min-h-12 rounded-xl border-white/[0.12] bg-white/[0.035] px-4 text-[0.94rem] text-[#fbf7ee] shadow-[inset_0_1px_2px_rgba(0,0,0,0.28)] placeholder:text-white/32 focus-visible:border-[#c8ad78]/70 focus-visible:ring-[#c8ad78]/25"
                />

                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2.5">
                <div class="flex items-center justify-between gap-3">
                    <Label
                        for="password"
                        class="text-[0.7rem] font-semibold uppercase tracking-[0.12em] text-[#c9c2b7]"
                    >
                        {{ copy.password }}
                    </Label>

                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs text-[#d9c397] decoration-[#d9c397]/35 underline-offset-4 hover:text-[#f2e4c9] hover:decoration-[#f2e4c9]/60"
                        :tabindex="5"
                    >
                        {{ copy.forgot_password }}
                    </TextLink>
                </div>

                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                     :placeholder="copy.password_placeholder"
                    class="min-h-12 rounded-xl border-white/[0.12] bg-white/[0.035] px-4 pr-11 text-[0.94rem] text-[#fbf7ee] shadow-[inset_0_1px_2px_rgba(0,0,0,0.28)] placeholder:text-white/32 focus-visible:border-[#c8ad78]/70 focus-visible:ring-[#c8ad78]/25"
                />

                <InputError :message="errors.password" />
            </div>

            <Label
                for="remember"
                class="mt-0.5 flex cursor-pointer items-center gap-3 text-sm text-white/62"
            >
                <Checkbox
                    id="remember"
                    name="remember"
                    :tabindex="3"
                    class="size-4 rounded border-white/25 data-[state=checked]:border-[#d6bf91] data-[state=checked]:bg-[#d6bf91] data-[state=checked]:text-[#17140f]"
                />

                <span>{{ copy.remember }}</span>
            </Label>

            <Button
                type="submit"
                class="mt-1 min-h-12 w-full rounded-xl border border-[#e2d0ad] bg-[linear-gradient(110deg,#aa8f69_0%,#dfcca7_54%,#b79b72_100%)] font-semibold tracking-[0.11em] text-[#17140f] shadow-[inset_0_1px_0_rgba(255,255,255,0.36),0_8px_24px_rgba(0,0,0,0.24)] transition duration-200 hover:-translate-y-0.5 hover:brightness-105 focus-visible:ring-2 focus-visible:ring-[#e2d0ad] focus-visible:ring-offset-2 focus-visible:ring-offset-[#0b0b0a] disabled:cursor-wait disabled:opacity-70 motion-reduce:transition-none"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                <span>{{ copy.submit }}</span>
            </Button>
        </div>
    </Form>
</template>
