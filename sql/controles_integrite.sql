-- Contrôles d'intégrité en lecture seule (aucune modification).
-- À exécuter dans phpMyAdmin (base concours_db, onglet SQL). Chaque requête doit renvoyer 0 ligne.

SELECT 'Doublon de candidat' AS controle, nom, prenom, date_naissance, id_annee, COUNT(*) AS n
FROM candidats GROUP BY id_annee, LOWER(TRIM(nom)), LOWER(TRIM(prenom)), date_naissance HAVING COUNT(*) > 1;

SELECT 'Salle hors du centre du candidat' AS controle, c.id_candidat, c.nom, c.prenom, s.nom_salle, ce.nom_centre AS centre_du_candidat
FROM candidats c JOIN salle s ON s.id_salle = c.id_salle JOIN centre ce ON ce.id_centre = c.id_centre
WHERE s.id_centre <> c.id_centre;

SELECT 'Parcours inconnu' AS controle, c.id_candidat, c.nom, c.parcours1, c.parcours2
FROM candidats c
WHERE (c.parcours1 IS NOT NULL AND c.parcours1 NOT IN (SELECT parcours_abrevie FROM parcours))
   OR (c.parcours2 IS NOT NULL AND c.parcours2 NOT IN (SELECT parcours_abrevie FROM parcours));

SELECT 'Moyenne différente du calcul pondéré' AS controle, n.id_notes, n.id_candidat, n.moyenne AS stockee,
       ROUND(SUM(nd.note * m.coef) / SUM(m.coef), 2) AS recalculee, COUNT(*) AS nb_matieres
FROM notes n JOIN notes_details nd ON nd.id_notes = n.id_notes JOIN matiere m ON m.id_matiere = nd.id_matiere
GROUP BY n.id_notes HAVING ABS(n.moyenne - recalculee) > 0.01;

SELECT 'Note sans aucun détail' AS controle, n.id_notes, n.id_candidat, n.moyenne
FROM notes n WHERE NOT EXISTS (SELECT 1 FROM notes_details nd WHERE nd.id_notes = n.id_notes);

SELECT 'Nombre d''années actives différent de 1' AS controle, COUNT(*) AS nb_actives FROM annees WHERE active = 1 HAVING COUNT(*) <> 1;

SELECT 'Mot de passe non haché' AS controle, pseudo FROM utilisateur WHERE mdp NOT LIKE '$2y$%';

SELECT 'Contrôlé mais dossier incomplet' AS controle, id_candidat, nom, prenom FROM candidats WHERE controle = 1 AND dossier_complet <> 1;

SELECT 'CIN ou e-mail valant 0' AS controle, id_candidat, nom, N_CIN, adresse_email FROM candidats WHERE N_CIN = '0' OR adresse_email = '0';
