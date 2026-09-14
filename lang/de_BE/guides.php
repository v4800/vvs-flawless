<?php

$base = require dirname(__DIR__).'/en_BE/guides.php';

return array_replace_recursive($base, [
    'links' => [
        'eyebrow' => 'Stein-Ratgeber',
        'title' => 'Diamant oder Moissanit?',
        'description' => 'Verstehe Optik, Feuer und die wichtigsten Unterschiede, bevor du eine Iced-Out-Uhr auswählst.',
        'cta' => 'Diamant und Moissanit vergleichen',
    ],
    'diamond_vs_moissanite' => [
        'seo_title' => 'Diamantuhr oder Moissanit: Was ist der Unterschied? | VVS FLAWLESS',
        'seo_description' => 'Diamantuhr, VVS-Moissanit oder Iced-Out? Vergleiche Brillanz, Feuer, Härte, Identifikation, VVS und Budget anhand von GIA-Quellen.',
        'eyebrow' => 'VVS-FLAWLESS-Ratgeber',
        'title' => 'Diamantuhr oder Moissanit: Was ist der Unterschied?',
        'intro' => 'Diamant und Moissanit können auf einer vollständig besetzten Uhr beide sehr hell wirken. Ein farbloser, gut geschliffener Moissanit kann Diamant optisch sehr nahekommen, behandelt Licht aber anders: Er erzeugt stärkeres Feuer und dadurch mehr farbige Lichtblitze.',
        'answer' => 'Für eine Iced-Out-Uhr ist VVS-Moissanit eine starke visuelle Alternative zu Diamant. Laut GIA besitzt Moissanit etwas mehr Brillanz und mehr als doppelt so viel Feuer wie Diamant. VVS-FLAWLESS-Modelle sind mit VVS-Moissanit in Farbe D besetzt: Die Steinart wird klar genannt, während der Stil den auffälligen Look vollständig besetzter Uhren bietet.',
        'comparison_title' => 'Was sich am Handgelenk wirklich unterscheidet',
        'comparison_intro' => 'Beide Steine sind stark brillant, sehen aber nicht identisch aus. Das sind die wichtigsten Unterschiede.',
        'columns' => [
            'criterion' => 'Kriterium',
            'diamond' => 'Diamant',
            'moissanite' => 'Moissanit',
        ],
        'rows' => [
            [
                'label' => 'Zusammensetzung',
                'diamond' => 'Diamant besteht aus Kohlenstoff.',
                'moissanite' => 'Der heute im Schmuck verwendete Moissanit ist laborerzeugtes Siliziumkarbid.',
            ],
            [
                'label' => 'Brillanz und Feuer',
                'diamond' => 'Starke Brillanz mit einem Verhältnis aus weißem Licht, Kontrast und Feuer, das unter anderem von der Schliffqualität abhängt.',
                'moissanite' => 'Laut GIA besitzt Moissanit etwas mehr Brillanz und mehr als doppelt so viel Feuer wie Diamant, wodurch mehr Regenbogenblitze entstehen.',
            ],
            [
                'label' => 'Härte',
                'diamond' => 'Diamant erreicht 10 auf der Mohs-Skala.',
                'moissanite' => 'Moissanit liegt bei etwa 9,25 auf der Mohs-Skala: sehr hart und langlebig, aber weniger hart als Diamant.',
            ],
            [
                'label' => 'VVS',
                'diamond' => 'VVS1 und VVS2 sind Diamant-Reinheitsgrade auf der GIA-Skala.',
                'moissanite' => 'Bei Moissanit ist „VVS“ eine kommerzielle Beschreibung der sichtbaren Reinheit und kein GIA-Reinheitsgrad.',
            ],
            [
                'label' => 'Identifikation',
                'diamond' => 'Geeignete Instrumente können bestätigen, ob ein Stein Diamant ist.',
                'moissanite' => 'Moissanit ist doppelbrechend und manche thermischen Tester reagieren ähnlich wie bei Diamant; ein geeignetes Gerät kann beide unterscheiden.',
            ],
            [
                'label' => 'Budget',
                'diamond' => 'Der Preis variiert stark je nach Diamantart, Gewicht, Farbe, Reinheit und Schliff.',
                'moissanite' => 'Moissanit ermöglicht einen stark besetzten Iced-Out-Look in der Regel zu einem zugänglicheren Budget.',
            ],
        ],
        'vvs_title' => 'Was bedeutet „VVS“ genau?',
        'vvs_text' => 'Bei Diamanten bedeutet VVS „Very, Very Slightly Included“ und bezeichnet die GIA-Reinheitsgrade VVS1 und VVS2. Das GIA wendet diese Diamant-Reinheitsskala nicht auf Moissanit an. Bei Moissanit beschreibt VVS daher kommerziell einen optisch sehr sauberen Stein; VVS FLAWLESS verwendet auf Produktseiten ausdrücklich die Formulierung „VVS-Moissanit“.',
        'tester_title' => 'Warum reagieren manche Diamanttester?',
        'tester_text' => 'Moissanit leitet Wärme sehr gut. Deshalb kann ein einfacher thermischer Diamanttester ähnlich reagieren wie bei Diamant. Für eine korrekte Unterscheidung braucht man einen Tester, der Diamant und Moissanit unterscheiden kann, oder eine geeignete gemmologische Analyse.',
        'report_eyebrow' => 'Das mitgelieferte Dokument verstehen',
        'report_title' => 'Was sollte man auf einem GRA-Bericht prüfen?',
        'report_intro' => 'Ein Moissanit kann mit einem GRA-Bericht geliefert werden, der Eigenschaften und eine Referenznummer aufführt. Das Dokument sollte zum erhaltenen Stein passen. Es beschreibt die angegebenen Eigenschaften des Moissanits; es ist kein GIA-Grading-Bericht.',
        'report_badge' => 'Lesehilfe · inoffizielle Darstellung',
        'report_note' => 'Das Layout kann je nach Bericht variieren. Zur Prüfung einer Referenz sollte nur die Webadresse verwendet werden, die auf dem tatsächlich mitgelieferten Dokument steht.',
        'report_fields' => [
            ['label' => 'Berichtsnummer', 'text' => 'Die eindeutige Referenz des Dokuments.'],
            ['label' => 'Beschreibung', 'text' => 'Die angegebene Steinart: Moissanit.'],
            ['label' => 'Form und Schliff', 'text' => 'Rund, Cushion, Princess oder eine andere Form.'],
            ['label' => 'Maße', 'text' => 'Die Abmessungen des Steins in Millimetern.'],
            ['label' => 'Gewicht', 'text' => 'Das angegebene Gewicht oder Karat-Äquivalent.'],
            ['label' => 'Farbe', 'text' => 'Die beworbene Farbe, zum Beispiel D.'],
            ['label' => 'Reinheit', 'text' => 'Die beworbene visuelle Qualität, zum Beispiel VVS1.'],
            ['label' => 'Schliffqualität', 'text' => 'Die angegebene Bewertung des Schliffs.'],
            ['label' => 'Politur', 'text' => 'Die Oberflächenqualität.'],
            ['label' => 'Symmetrie', 'text' => 'Die Regelmäßigkeit von Schliff und Facetten.'],
            ['label' => 'Fluoreszenz', 'text' => 'Die angegebene Reaktion unter UV-Licht.'],
            ['label' => 'Inschrift', 'text' => 'Die lasergravierte Nummer, falls vorhanden.'],
        ],
        'choice_title' => 'Welcher Stein passt besser zu dir?',
        'choice_diamond_title' => 'Du möchtest ausdrücklich Diamant',
        'choice_diamond_text' => 'Wähle Diamant, wenn dir die Steinart selbst, Seltenheit oder materieller Wert wichtig sind. Prüfe Diamantart, angegebene Qualität und die vom Verkäufer bereitgestellten Dokumente.',
        'choice_moissanite_title' => 'Dir geht es vor allem um den Iced-Out-Look',
        'choice_moissanite_text' => 'Wähle Moissanit, wenn du vor allem einen dichten Pavé-Look mit starker Brillanz und mehr Feuer suchst, der Diamant optisch sehr nahekommen kann und meist zugänglicher ist.',
        'cta_title' => 'VVS-FLAWLESS-Modelle entdecken',
        'cta_text' => 'Entdecke Uhren mit VVS-Moissanit in Farbe D, erhältlich auf Reservierung. Der Einstiegspreis jedes Modells wird bereits in der Kollektion angezeigt.',
        'cta_label' => 'Kollektion ansehen',
        'sources_title' => 'Mehr erfahren',
        'sources_intro' => 'Die technischen Angaben in diesem Ratgeber wurden anhand von Quellen des Gemological Institute of America (GIA) geprüft.',
        'sources' => [
            [
                'label' => 'GIA — 4Cs Clarity',
                'url' => 'https://www.gia.edu/gia-about/4cs-clarity',
            ],
            [
                'label' => 'GIA — An Introduction to Imitation Diamonds & Other Gems',
                'url' => 'https://www.gia.edu/gem-imitation',
            ],
            [
                'label' => 'GIA 4Cs — Simulants, Moissanite and Lab-Grown Diamonds',
                'url' => 'https://4cs.gia.edu/en-us/simulants-moissanite-and-lab-grown-diamonds/',
            ],
            [
                'label' => 'GIA 4Cs — Diamond Alternatives: Moissanite',
                'url' => 'https://4cs.gia.edu/en-us/blog/diamond-alternatives-engagement-rings/',
            ],
            [
                'label' => 'GIA — Synthetic Moissanite with Fraudulent GIA Inscription',
                'url' => 'https://hongkong.gia.edu/gems-gemology/fall-2020-labnotes-synthetic-moissanite-fraudulent-gia-inscription',
            ],
        ],
    ],
]);
