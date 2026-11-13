-- Mise en conformité de l'intégrité de la base concours_db (MariaDB 10.4).
-- Peut être relancé sans danger. Si une instruction échoue (doublon existant),
-- corrige les données signalées par controles_integrite.sql puis relance.

-- 1) Valeurs "0" saisies à la place d'un champ vide
UPDATE candidats SET N_CIN = NULL WHERE N_CIN IN ('0', '');
UPDATE candidats SET Lieu_CIN = NULL WHERE Lieu_CIN IN ('0', '');
UPDATE candidats SET adresse_email = NULL WHERE adresse_email IN ('0', '');

-- 2) Unicité : empêche les doublons même si deux personnes saisissent en même temps
ALTER TABLE notes         ADD UNIQUE INDEX IF NOT EXISTS uq_notes_candidat_annee (id_candidat, id_annee);
ALTER TABLE notes_details ADD UNIQUE INDEX IF NOT EXISTS uq_notes_details_matiere (id_notes, id_matiere);
ALTER TABLE convocation   ADD UNIQUE INDEX IF NOT EXISTS uq_convocation_candidat_annee (id_candidat, id_annee);
ALTER TABLE candidats     ADD UNIQUE INDEX IF NOT EXISTS uq_candidat_identite (id_annee, nom, prenom, date_naissance);

-- 3) Index pour les listes et statistiques
ALTER TABLE candidats ADD INDEX IF NOT EXISTS idx_cand_annee_niveau_parcours (id_annee, niveau, parcours1);

-- 4) Contraintes de valeurs
ALTER TABLE notes_details DROP CONSTRAINT IF EXISTS chk_note_0_20;
ALTER TABLE notes_details ADD CONSTRAINT chk_note_0_20 CHECK (note BETWEEN 0 AND 20);
ALTER TABLE notes DROP CONSTRAINT IF EXISTS chk_moyenne_0_20;
ALTER TABLE notes ADD CONSTRAINT chk_moyenne_0_20 CHECK (moyenne IS NULL OR moyenne BETWEEN 0 AND 20);
ALTER TABLE candidats DROP CONSTRAINT IF EXISTS chk_annee_bacc;
ALTER TABLE candidats ADD CONSTRAINT chk_annee_bacc CHECK (annee_bacc IS NULL OR annee_bacc BETWEEN 1950 AND 2100);
