# Structure du projet Concours IST-D

```
concours/
├── index.php                  Tableau de bord (page d'accueil)
├── login.php                  Page de connexion
├── deconnect.php              Déconnexion
├── .htaccess                  Protections Apache (fichiers sensibles, cookies de session)
│
├── config/
│   └── connect.php            Connexion à la base de données (PDO)
│
├── includes/                  Code partagé (jamais appelé directement par le navigateur)
│   ├── base.php               ROOT_DIR, BASE_URL et table des pages (PAGES)
│   ├── auth.php               Contrôle de connexion et de rôle : exiger_role([...])
│   ├── template.php           Mise en page commune (menu, barre du haut, fil d'Ariane)
│   ├── get_annee.php          Années scolaires et année sélectionnée
│   ├── journal_helper.php     Journal d'activité et notifications
│   └── validation_candidat.php  Règles de saisie (nom, téléphone, CIN, dates...)
│
├── modules/
│   ├── auth/                  login_traitement, mot_de_passe_oublie
│   ├── candidats/             saisi_dts, saisi_dtss, saisi_ing, enregistre_candidat,
│   │                          liste_saisi, update_candidat_ajax
│   ├── convocations/          convocation*, convoc_*, imprime_*, edt_*
│   ├── listes/                liste_presence*, liste_niveau, effectif_salle,
│   │                          liste_modif_salle, liste_etiquette, repartir_salles
│   ├── jurys/                 affiche_jury
│   ├── deliberation/          delib (saisie des notes, moyenne, résultats)
│   ├── utilisateurs/          liste, ajout, modification, suppression des comptes
│   └── administration/        journal, notifications, new_year, date_concours
│
├── assets/
│   ├── css/concours.css       Thème visuel (bleu piscine, blanc, rouge)
│   ├── js/validation_saisie.js  Contrôle de saisie en direct
│   ├── img/                   Logos et images
│   ├── plugins/ et dist/      AdminLTE et ses bibliothèques
│
├── sql/                       Scripts de base de données (01_base, 02_integrite, 03_...)
└── docs/                      Documentation
```

## Règles de rangement
- Une page de l'application va dans `modules/<module>/`.
- Le code partagé va dans `includes/`, la configuration dans `config/`.
- Pour inclure un fichier : `require __DIR__ . '/../../includes/auth.php';` (chemin depuis la page).
- Pour écrire un lien dans une page : chemin relatif depuis la page (`../utilisateurs/utilisateur.php`).
  Dans `includes/template.php` (commun à toutes les pages), utiliser `<?= BASE_URL ?>/modules/...`.
- Toute nouvelle page doit être ajoutée à la table `PAGES` de `includes/base.php`
  si elle est la cible d'une notification.
