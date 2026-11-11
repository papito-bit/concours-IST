@echo off
chcp 65001 >nul
rem === Installateur : journal d'activite + notifications ===
set "SRC=%~dp0fichiers\"
set "PROJET=C:\xampp83\htdocs\concours\"
set "MYSQL=C:\xampp83\mysql\bin\mysql.exe"

if not exist "%PROJET%index.php" (
  echo ERREUR : projet introuvable dans %PROJET%
  echo Verifie que le dossier du projet s'appelle bien concours.
  pause
  exit /b 1
)

echo 1/3 Sauvegarde des fichiers remplaces dans _avant_maj ...
if not exist "%PROJET%_avant_maj" mkdir "%PROJET%_avant_maj"
for %%F in ("%SRC%*.php") do (
  if exist "%PROJET%%%~nxF" copy /y "%PROJET%%%~nxF" "%PROJET%_avant_maj\" >nul
)

echo 2/3 Copie des nouveaux fichiers ...
for %%F in ("%SRC%*.php") do copy /y "%%F" "%PROJET%" >nul

echo 3/3 Mise a jour de la base de donnees ...
if exist "%MYSQL%" (
  "%MYSQL%" -u root concours_db < "%~dp0base.sql"
  if errorlevel 1 (
    echo ATTENTION : la base n'a pas ete mise a jour automatiquement.
    echo Ouvre phpMyAdmin, base concours_db, onglet SQL, colle base.sql et execute.
  ) else (
    echo Base mise a jour.
  )
) else (
  echo mysql.exe introuvable : ouvre phpMyAdmin, base concours_db, onglet SQL,
  echo colle le contenu de base.sql et clique sur Executer.
)
echo.
echo Termine. Ouvre http://localhost/concours et reconnecte-toi.
pause
