@extends('vvs-reviews.layout')
@section('content')
<h1>Avis clients</h1>
<p class="muted">Des expériences partagées, sans témoignage inventé. Les achats ne sont pas automatiquement vérifiés.</p>
<a href="#laisser-un-avis">Laisser mon avis</a>
@auth @if(auth()->user()->is_admin) · <a href="{{ route('vvs.reviews.moderate') }}">Modération</a> @endif @endauth
<div class="grid">
@forelse($reviews as $review)
    <article class="panel">
        <h2>{{ $review->display_name }}</h2>
        <p class="gold" aria-label="Note : {{ $review->rating }} sur 5">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }} · {{ $review->rating }}/5</p>
        <p class="review-body">{{ $review->body }}</p>
        <p class="muted">Déposé le {{ \Illuminate\Support\Carbon::parse($review->created_at)->format('d/m/Y') }}</p>
    </article>
@empty
    <p class="panel">Aucun avis publié pour le moment. Vous avez eu une expérience avec VVS FLAWLESS ? Partagez-la.</p>
@endforelse
</div>
<nav class="nav" aria-label="Pagination des avis">
    @if($reviews->previousPageUrl())<a href="{{ $reviews->previousPageUrl() }}">← Précédent</a>@endif
    @if($reviews->hasMorePages())<a href="{{ $reviews->nextPageUrl() }}">Suivant →</a>@endif
</nav>
<form id="laisser-un-avis" class="panel" action="{{ route('vvs.reviews.store') }}" method="post">
    @csrf
    <h2>Racontez votre expérience</h2>
    <p class="muted">Votre prénom ou pseudonyme, votre note, votre texte et la date seront publics après modération. Ne partagez pas de coordonnées ni de numéro de commande.</p>
    <label for="display_name">Prénom ou pseudonyme public</label>
    <input id="display_name" name="display_name" minlength="2" maxlength="50" value="{{ old('display_name') }}" required>
    <label for="rating">Note</label>
    <select id="rating" name="rating" required><option value="">Choisir une note</option>@for($i=1;$i<=5;$i++)<option value="{{ $i }}" @selected((string)old('rating') === (string)$i)>{{ $i }} / 5</option>@endfor</select>
    <label for="body">Votre avis (20 à 2 000 caractères)</label>
    <textarea id="body" name="body" minlength="20" maxlength="2000" required>{{ old('body') }}</textarea>
    <div class="trap" aria-hidden="true"><label for="website">Laisser vide</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
    <label><input name="experience" type="checkbox" value="1" required @checked(old('experience'))> Je décris une expérience personnelle réelle et j’accepte la publication des informations ci-dessus.</label>
    <button type="submit">Envoyer mon avis</button>
    <p class="muted">Les avis ne s’affichent pas immédiatement. <a href="/confidentialite">Confidentialité et contact</a>.</p>
</form>
<section class="panel"><h2>Règles de modération</h2><p>Toutes les notes sont acceptées. Une critique négative n’est pas un motif de refus. Seuls les spams, données personnelles, injures ou menaces, contenus hors sujet et expériences fictives établies sont refusés. Les avis sont affichés du plus récent au plus ancien. Il n’y a aucune récompense liée à la note et aucun badge « achat vérifié ».</p><p>Pour demander une correction ou un retrait, contactez-nous via les coordonnées du site en indiquant votre pseudonyme et la date de l’avis.</p></section>
@endsection
