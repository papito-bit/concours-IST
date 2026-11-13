-- Données de saisie : nettoyage et garde-fous (MariaDB 10.4). Peut être relancé sans danger.

-- 1) Valeurs "0" enregistrées à la place d'un champ vide (anciens formulaires)
UPDATE candidats SET N_CIN = NULL WHERE N_CIN IN ('0', '');
UPDATE candidats SET Lieu_CIN = NULL WHERE Lieu_CIN IN ('0', '');
UPDATE candidats SET adresse_email = NULL WHERE adresse_email IN ('0', '');
UPDATE candidats SET Date_CIN = NULL WHERE Date_CIN = '0000-00-00';

-- 2) Noms toujours en majuscules
UPDATE candidats SET nom = UPPER(TRIM(nom));

-- 3) Un même CIN ne peut pas être utilisé par deux candidats dans la même année
ALTER TABLE candidats ADD UNIQUE INDEX IF NOT EXISTS uq_candidat_cin (id_annee, N_CIN);

-- 4) Téléphone : mobile 032/033/034/037/038 ou fixe 020, sur 10 chiffres
ALTER TABLE candidats DROP CONSTRAINT IF EXISTS chk_telephone;
ALTER TABLE candidats ADD CONSTRAINT chk_telephone
  CHECK (telephone IS NULL OR telephone REGEXP '^(03[23478][0-9]{7}|020[0-9]{7})$');
