<script setup>
defineProps({ watch: { type: Object, required: true } });
</script>

<template>
    <div class="self-center rounded-3xl border border-amber-300/20 bg-black/70 p-6 sm:p-9" lang="fr">
        <p class="text-xs font-bold tracking-[0.3em] text-amber-300">VVS FLAWLESS</p>
        <h1 class="vvs-display-title mt-6 text-4xl sm:text-5xl">{{ watch.name }}</h1>
        <p class="mt-6 leading-7 text-zinc-300">{{ watch.description }}</p>
        <p class="mt-8 text-xs tracking-widest text-zinc-400 uppercase">Prix du modèle présenté</p>
        <p class="mt-2 text-5xl font-bold text-amber-200">{{ Number(watch.price).toFixed(0) }} €</p>
        <dl class="mt-8 space-y-4 border-t border-white/10 pt-6 text-sm">
            <div><dt class="text-zinc-400">Pierres</dt><dd class="mt-1">Moissanite · poids total annoncé : 20–30 ct</dd></div>
            <div><dt class="text-zinc-400">À confirmer avant commande</dt><dd class="mt-1">Poids des pierres de cet exemplaire, dimensions et référence du mouvement.</dd></div>
        </dl>
        <p class="mt-5 text-xs leading-6 text-zinc-400">Décor des photos retouché. Demandez une vidéo de l’exemplaire proposé avant de confirmer votre choix.</p>
        <a href="#reservation" class="mt-8 block rounded-xl bg-amber-300 px-5 py-4 text-center font-bold text-black focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-200">Réserver cette montre</a>
    </div>
</template>
