@extends('vvs-reviews.layout')
@section('content')
<style>
    main{max-width:1240px;padding-top:36px;padding-bottom:72px}
    .moderation-page{position:relative;isolation:isolate;padding-top:46px}
    .moderation-page::before{position:absolute;top:-80px;left:-180px;z-index:-1;width:520px;height:520px;border-radius:999px;background:radial-gradient(circle,rgba(217,166,46,.11),transparent 68%);content:'';pointer-events:none}
    .moderation-header{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:36px;align-items:end;padding-bottom:34px;border-bottom:1px solid rgba(255,255,255,.09)}
    .moderation-eyebrow{margin:0;color:#f2d58a;font-size:10px;font-weight:800;letter-spacing:.28em;text-transform:uppercase}
    .moderation-title{max-width:760px;margin:12px 0 0;font-size:clamp(40px,6vw,68px);letter-spacing:-.035em;color:#fff8df}
    .moderation-copy{max-width:760px;margin:18px 0 0;color:#a1a1aa;font-size:15px;line-height:1.8}
    .moderation-link{display:inline-flex;min-height:46px;align-items:center;gap:10px;padding:0 18px;border:1px solid rgba(242,213,138,.22);border-radius:12px;background:rgba(255,255,255,.025);color:#f2d58a;text-decoration:none;font-size:13px;font-weight:750;white-space:nowrap;transition:border-color 180ms ease,background 180ms ease,color 180ms ease}
    .moderation-link:hover{border-color:rgba(242,213,138,.42);background:rgba(217,166,46,.055);color:#fff8df}
    .moderation-link svg{width:16px;height:16px}
    .moderation-list{display:grid;gap:18px;margin-top:28px}
    .moderation-card{margin:0;border:1px solid rgba(255,255,255,.095);border-radius:22px;background:linear-gradient(145deg,rgba(255,255,255,.035),transparent 42%),rgba(7,7,8,.92);padding:24px;box-shadow:0 24px 65px rgba(0,0,0,.22),inset 0 1px 0 rgba(255,255,255,.045)}
    .moderation-card-head{display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:18px;padding-bottom:20px;border-bottom:1px solid rgba(255,255,255,.08)}
    .moderation-card h2{margin:0;color:#fff8df;font-size:25px}
    .review-meta{margin:7px 0 0;color:#71717a;font-size:12px}
    .status-badge{display:inline-flex;align-items:center;min-height:30px;padding:0 11px;border:1px solid rgba(255,255,255,.11);border-radius:999px;background:rgba(255,255,255,.025);color:#d4d4d8;font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}
    .status-badge.published{border-color:rgba(134,239,172,.25);color:#bbf7d0;background:rgba(34,197,94,.06)}
    .status-badge.rejected{border-color:rgba(252,165,165,.24);color:#fecaca;background:rgba(239,68,68,.055)}
    .status-badge.pending{border-color:rgba(242,213,138,.23);color:#f2d58a;background:rgba(217,166,46,.05)}
    .review-body{margin:22px 0;color:#e4e4e7;font-size:15px;line-height:1.8}
    .moderation-reason{margin:0 0 20px;padding:12px 14px;border-left:2px solid rgba(242,213,138,.45);background:rgba(217,166,46,.035);color:#a1a1aa;font-size:13px}
    .moderation-form{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.25fr) auto;gap:16px;align-items:end}
    .moderation-field label{margin:0 0 7px;color:#a1a1aa;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
    .moderation-field select{min-height:48px;border:1px solid rgba(255,255,255,.11);border-radius:12px;background:#050506;color:#f4f4f5;padding:0 14px;box-shadow:inset 0 1px 0 rgba(255,255,255,.025)}
    .moderation-field select:focus{border-color:rgba(242,213,138,.58);outline:none;box-shadow:0 0 0 3px rgba(217,166,46,.08)}
    .moderation-submit{min-height:48px;margin:0;padding:0 20px;border:1px solid rgba(255,248,223,.46);border-radius:12px;background:linear-gradient(115deg,#9a6414,#d9a62e 38%,#f2d58a 72%,#c88d1d);color:#080808;font-weight:850;box-shadow:0 12px 28px rgba(180,115,16,.15)}
    .moderation-submit:hover{filter:brightness(1.05)}
    .moderation-empty{margin-top:28px;border:1px solid rgba(255,255,255,.095);border-radius:24px;background:linear-gradient(145deg,rgba(255,255,255,.035),transparent 46%),rgba(7,7,8,.9);padding:52px 30px;text-align:center;box-shadow:0 26px 70px rgba(0,0,0,.24),inset 0 1px 0 rgba(255,255,255,.04)}
    .moderation-empty-icon{display:flex;width:52px;height:52px;align-items:center;justify-content:center;margin:0 auto 18px;border:1px solid rgba(242,213,138,.24);border-radius:50%;background:rgba(217,166,46,.045);color:#f2d58a}
    .moderation-empty-icon svg{width:22px;height:22px}
    .moderation-empty h2{margin:0;color:#fff8df;font-size:27px}
    .moderation-empty p{max-width:520px;margin:10px auto 0;color:#71717a;font-size:14px}
    .moderation-pagination{margin-top:24px}
    .moderation-pagination a{display:inline-flex;min-height:42px;align-items:center;padding:0 15px;border:1px solid rgba(255,255,255,.1);border-radius:10px;text-decoration:none}
    @media(max-width:820px){.moderation-header{grid-template-columns:1fr;align-items:start}.moderation-form{grid-template-columns:1fr}.moderation-submit{width:100%}}
    @media(max-width:560px){main{padding-left:16px;padding-right:16px}.moderation-page{padding-top:32px}.moderation-card,.moderation-empty{border-radius:18px;padding:20px}.moderation-title{font-size:40px}}
    @media(prefers-reduced-motion:reduce){.moderation-page *{transition:none!important}}
</style>

<section class="moderation-page">
    <header class="moderation-header">
        <div>
            <p class="moderation-eyebrow">Administration · Avis clients</p>
            <h1 class="moderation-title">Modération des avis</h1>
            <p class="moderation-copy">Publiez les avis positifs comme négatifs. Ne refusez jamais un avis pour sa note et ne modifiez pas le texte du client.</p>
        </div>

        <a class="moderation-link" href="{{ route('vvs.reviews.index') }}">
            Voir la page publique
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M8 5l7 7-7 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </header>

    <div class="moderation-list">
        @forelse($reviews as $review)
            <article class="moderation-card">
                <div class="moderation-card-head">
                    <div>
                        <h2>#{{ $review->id }} · {{ $review->display_name }} · {{ $review->rating }}/5</h2>
                        <p class="review-meta">Reçu le {{ $review->created_at }}</p>
                    </div>

                    <span class="status-badge {{ $review->status }}">
                        @if($review->status === 'published') Publié
                        @elseif($review->status === 'rejected') Refusé
                        @else En attente
                        @endif
                    </span>
                </div>

                <p class="review-body">{{ $review->body }}</p>

                @if($review->moderation_reason)
                    <p class="moderation-reason">Motif actuel : {{ $review->moderation_reason }}</p>
                @endif

                <form class="moderation-form" method="post" action="{{ route('vvs.reviews.update', $review->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="moderation-field">
                        <label for="status-{{ $review->id }}">Décision</label>
                        <select id="status-{{ $review->id }}" name="status">
                            <option value="pending" @selected($review->status==='pending')>En attente</option>
                            <option value="published" @selected($review->status==='published')>Publier</option>
                            <option value="rejected" @selected($review->status==='rejected')>Refuser / retirer</option>
                        </select>
                    </div>

                    <div class="moderation-field">
                        <label for="reason-{{ $review->id }}">Motif obligatoire pour refuser</label>
                        <select id="reason-{{ $review->id }}" name="reason">
                            <option value="">Sans motif (publication ou attente)</option>
                            @foreach(['spam','donnees-personnelles','injures-menaces','hors-sujet','experience-fictive-etablie'] as $reason)
                                <option value="{{ $reason }}" @selected($review->moderation_reason===$reason)>{{ $reason }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="moderation-submit" type="submit">Enregistrer</button>
                </form>
            </article>
        @empty
            <div class="moderation-empty">
                <div class="moderation-empty-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 8h10M7 12h6M5 4h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-7l-4.5 3V17H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2>Aucun avis reçu</h2>
                <p>Les nouveaux avis clients apparaîtront ici dès qu'ils seront envoyés pour modération.</p>
            </div>
        @endforelse
    </div>

    <nav class="nav moderation-pagination" aria-label="Pagination des avis">
        @if($reviews->previousPageUrl())
            <a href="{{ $reviews->previousPageUrl() }}">Précédent</a>
        @endif
        @if($reviews->hasMorePages())
            <a href="{{ $reviews->nextPageUrl() }}">Suivant</a>
        @endif
    </nav>
</section>
@endsection
