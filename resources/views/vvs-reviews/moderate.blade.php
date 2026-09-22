@extends('vvs-reviews.layout')
@section('content')
<h1>Modération des avis</h1>
<p>Publier les avis positifs comme négatifs. Ne jamais refuser un avis pour sa note. Aucune modification du texte du client.</p>
<a href="{{ route('vvs.reviews.index') }}">Voir la page publique</a>
@forelse($reviews as $review)
<article class="panel"><h2>#{{ $review->id }} · {{ $review->display_name }} · {{ $review->rating }}/5</h2>
<p class="review-body">{{ $review->body }}</p><p>Statut : {{ $review->status }} · {{ $review->created_at }}</p>
@if($review->moderation_reason)<p>Motif : {{ $review->moderation_reason }}</p>@endif
<form method="post" action="{{ route('vvs.reviews.update', $review->id) }}">@csrf @method('PATCH')
<label for="status-{{ $review->id }}">Décision</label><select id="status-{{ $review->id }}" name="status"><option value="pending" @selected($review->status==='pending')>En attente</option><option value="published" @selected($review->status==='published')>Publier</option><option value="rejected" @selected($review->status==='rejected')>Refuser / retirer</option></select>
<label for="reason-{{ $review->id }}">Motif obligatoire pour refuser</label><select id="reason-{{ $review->id }}" name="reason"><option value="">Sans motif (publication ou attente)</option>@foreach(['spam','donnees-personnelles','injures-menaces','hors-sujet','experience-fictive-etablie'] as $reason)<option value="{{ $reason }}" @selected($review->moderation_reason===$reason)>{{ $reason }}</option>@endforeach</select>
<button type="submit">Enregistrer</button></form></article>
@empty<p>Aucun avis reçu.</p>@endforelse
<nav class="nav">@if($reviews->previousPageUrl())<a href="{{ $reviews->previousPageUrl() }}">← Précédent</a>@endif @if($reviews->hasMorePages())<a href="{{ $reviews->nextPageUrl() }}">Suivant →</a>@endif</nav>
@endsection
