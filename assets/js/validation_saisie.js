/* validation_saisie.js : contrôle de saisie en direct des formulaires candidat.
   Confort de l'utilisateur uniquement : les mêmes règles sont revérifiées par le serveur
   (validation_candidat.php), qui fait foi. */
(function () {
  'use strict';

  var LETTRES = /^[\p{L}]+(?:[\p{L}\s'’\-]*[\p{L}])?$/u;

  // ---------- Dates ----------
  function aujourdhui() { var d = new Date(); d.setHours(0, 0, 0, 0); return d; }
  function ilYa(ans) { var d = aujourdhui(); d.setFullYear(d.getFullYear() - ans); return d; }
  function plus(d, ans) { var x = new Date(d.getTime()); x.setFullYear(x.getFullYear() + ans); return x; }
  function iso(d) {
    var m = d.getMonth() + 1, j = d.getDate();
    return d.getFullYear() + '-' + (m < 10 ? '0' : '') + m + '-' + (j < 10 ? '0' : '') + j;
  }
  function parse(s) {
    var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(s || '');
    if (!m) { return null; }
    var d = new Date(+m[1], +m[2] - 1, +m[3]);
    return (d.getFullYear() === +m[1] && d.getMonth() === +m[2] - 1 && d.getDate() === +m[3]) ? d : null;
  }
  function fr(d) { return ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear(); }

  // ---------- Mise en forme ----------
  function titre(v) {
    return v.toLowerCase().replace(/(^|[\s'’\-])(\p{L})/gu, function (m, a, b) { return a + b.toUpperCase(); });
  }
  function filtrer(el, interdit, transformer) {
    var avant = el.value, pos = el.selectionStart;
    var apres = avant.replace(interdit, '');
    if (transformer) { apres = transformer(apres); }
    if (apres !== avant) {
      el.value = apres;
      var p = Math.max(0, pos - (avant.length - apres.length));
      try { el.setSelectionRange(p, p); } catch (e) { /* champ sans curseur */ }
    }
  }
  function chiffres(v) { return v.replace(/\D/g, ''); }
  function formaterTelephone(v) {
    var c = v.replace(/[\s.\-()]/g, '').replace(/^(\+261|00261|261)/, '0');
    return /^\d{10}$/.test(c) ? c.replace(/^(\d{3})(\d{2})(\d{3})(\d{2})$/, '$1 $2 $3 $4') : v;
  }

  // ---------- Règles : retournent un message d'erreur, ou '' si valide ----------
  var annee = new Date().getFullYear();
  var REGLES = {
    nom: function (v) {
      v = v.replace(/\s+/g, ' ').trim();
      if (v.length < 2 || !LETTRES.test(v)) { return 'Le nom ne doit contenir que des lettres (2 caractères minimum).'; }
      return '';
    },
    prenom: function (v) {
      v = v.replace(/\s+/g, ' ').trim();
      if (v !== '' && !LETTRES.test(v)) { return 'Le prénom ne doit contenir que des lettres.'; }
      return '';
    },
    date_naissance: function (v) {
      var d = parse(v);
      if (!d) { return 'Date invalide.'; }
      if (d < ilYa(60) || d > ilYa(14)) { return 'Âge autorisé : de 14 à 60 ans (du ' + fr(ilYa(60)) + ' au ' + fr(ilYa(14)) + ').'; }
      return '';
    },
    lieu_naissance: function (v) { return lieu(v, 'Le lieu de naissance'); },
    Lieu_CIN: function (v) { return lieu(v, 'Le lieu de délivrance'); },
    adresse: function (v) {
      v = v.trim();
      if (v.length < 3 || v.length > 250 || /[<>]/.test(v)) { return 'Adresse invalide (3 à 250 caractères, sans < ni >).'; }
      return '';
    },
    telephone: function (v) {
      var c = v.replace(/[\s.\-()]/g, '').replace(/^(\+261|00261|261)/, '0');
      return /^(03[23478]\d{7}|020\d{7})$/.test(c) ? '' : 'Numéro invalide. Exemple : 034 12 345 67 (032, 033, 034, 037 ou 038).';
    },
    N_CIN: function (v) { return /^\d{12}$/.test(v.replace(/[\s.\-]/g, '')) ? '' : 'Le CIN doit comporter exactement 12 chiffres.'; },
    Date_CIN: function (v, f) {
      var d = parse(v);
      if (!d) { return 'Date invalide.'; }
      var nais = parse(valeur(f, 'date_naissance'));
      var min = nais ? plus(nais, 12) : new Date(1950, 0, 1);
      if (d < min || d > aujourdhui()) { return 'Date de CIN impossible (entre le ' + fr(min) + ' et aujourd\'hui).'; }
      return '';
    },
    adresse_email: function (v) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()) ? '' : 'Adresse e-mail invalide.';
    },
    annee_bacc: function (v, f) {
      if (!/^\d{4}$/.test(v) || +v < 1990 || +v > annee) { return 'Année invalide (entre 1990 et ' + annee + ').'; }
      var nais = parse(valeur(f, 'date_naissance'));
      if (nais && +v < nais.getFullYear() + 14) { return 'Année incompatible avec la date de naissance.'; }
      return '';
    },
    num_arrivee: function (v) { return /^[A-Za-z0-9\/\-]{1,25}$/.test(v) ? '' : "Numéro d'arrivée invalide (chiffres, lettres, / et -)."; },
    date_arrivee: function (v) {
      var d = parse(v);
      if (!d) { return 'Date invalide.'; }
      if (d < ilYa(3) || d > aujourdhui()) { return "La date d'arrivée doit être comprise entre le " + fr(ilYa(3)) + " et aujourd'hui."; }
      return '';
    }
  };
  function lieu(v, libelle) {
    v = v.replace(/\s+/g, ' ').trim();
    if (v.length < 2 || v.length > 100 || !/^[\p{L}\p{N}][\p{L}\p{N}\s'’\-.,\/()]*$/u.test(v)) {
      return libelle + ' est invalide (lettres et chiffres, 2 à 100 caractères).';
    }
    return '';
  }
  function valeur(form, nom) { var e = form.elements[nom]; return e ? e.value : ''; }

  // ---------- Affichage des messages ----------
  var style = document.createElement('style');
  style.textContent = '.saisie-erreur{color:#d62f37;font-size:.85rem;margin-top:4px;font-weight:600}' +
    '.form-control.is-invalid{border-color:#d62f37!important}.form-control.is-valid{border-color:#00b4d8!important}';
  document.head.appendChild(style);

  function message(el, texte) {
    var zone = el.parentNode.querySelector('.saisie-erreur[data-pour="' + el.name + '"]');
    if (!texte) {
      if (zone) { zone.remove(); }
      el.classList.remove('is-invalid');
      el.classList.toggle('is-valid', el.value.trim() !== '');
      return;
    }
    if (!zone) {
      zone = document.createElement('div');
      zone.className = 'saisie-erreur';
      zone.setAttribute('data-pour', el.name);
      el.parentNode.insertBefore(zone, el.nextSibling);
    }
    zone.textContent = texte;
    el.classList.remove('is-valid');
    el.classList.add('is-invalid');
  }

  function verifier(el, f) {
    var v = (el.value || '').trim();
    var requis = el.hasAttribute('required');
    var texte = '';
    if (v === '') {
      if (requis) { texte = el.tagName === 'SELECT' ? 'Sélectionnez une valeur.' : 'Ce champ est obligatoire.'; }
    } else if (REGLES[el.name]) {
      texte = REGLES[el.name](el.value, f);
    }
    message(el, texte);
    return texte === '';
  }

  // ---------- Installation sur chaque formulaire de saisie ----------
  function installer(f) {
    if (f.__saisieInstallee) { return; }
    f.__saisieInstallee = true;
    f.setAttribute('novalidate', 'novalidate');
    var el = function (n) { return f.elements[n]; };
    var champ;

    if ((champ = el('nom'))) {
      champ.setAttribute('maxlength', '100'); champ.setAttribute('autocomplete', 'off');
      champ.addEventListener('input', function () { filtrer(this, /[^\p{L}\s'’\-]/gu, function (v) { return v.toUpperCase(); }); });
      champ.addEventListener('blur', function () { this.value = this.value.replace(/\s+/g, ' ').trim(); });
    }
    if ((champ = el('prenom'))) {
      champ.setAttribute('maxlength', '150');
      champ.addEventListener('input', function () { filtrer(this, /[^\p{L}\s'’\-]/gu); });
      champ.addEventListener('blur', function () { this.value = titre(this.value.replace(/\s+/g, ' ').trim()); });
    }
    ['lieu_naissance', 'Lieu_CIN'].forEach(function (n) {
      if ((champ = el(n))) {
        champ.setAttribute('maxlength', '100');
        champ.addEventListener('blur', function () { this.value = titre(this.value.replace(/\s+/g, ' ').trim()); });
      }
    });
    if ((champ = el('telephone'))) {
      champ.setAttribute('inputmode', 'tel'); champ.setAttribute('maxlength', '17'); champ.setAttribute('placeholder', '034 12 345 67');
      champ.addEventListener('input', function () { filtrer(this, /[^\d+\s.\-()]/g); });
      champ.addEventListener('blur', function () { this.value = formaterTelephone(this.value.trim()); });
    }
    if ((champ = el('N_CIN'))) {
      champ.setAttribute('inputmode', 'numeric'); champ.setAttribute('maxlength', '15'); champ.setAttribute('placeholder', '123 456 789 012');
      champ.addEventListener('input', function () {
        this.value = chiffres(this.value).slice(0, 12).replace(/(\d{3})(?=\d)/g, '$1 ');
      });
    }
    if ((champ = el('adresse_email'))) {
      champ.setAttribute('maxlength', '200');
      champ.addEventListener('blur', function () { this.value = this.value.trim().toLowerCase(); });
    }
    if ((champ = el('annee_bacc'))) {
      champ.type = 'text'; champ.setAttribute('inputmode', 'numeric'); champ.setAttribute('maxlength', '4'); champ.setAttribute('placeholder', 'AAAA');
      champ.addEventListener('input', function () { this.value = chiffres(this.value).slice(0, 4); });
    }
    if ((champ = el('num_arrivee'))) {
      champ.setAttribute('maxlength', '25');
      champ.addEventListener('input', function () { filtrer(this, /[^A-Za-z0-9\/\-]/g); });
    }

    // Bornes des calendriers
    if ((champ = el('date_naissance'))) { champ.min = iso(ilYa(60)); champ.max = iso(ilYa(14)); }
    if ((champ = el('date_arrivee'))) { champ.min = iso(ilYa(3)); champ.max = iso(aujourdhui()); }
    function bornesCIN() {
      var c = el('Date_CIN');
      if (!c) { return; }
      var nais = parse(valeur(f, 'date_naissance'));
      c.min = iso(nais ? plus(nais, 12) : new Date(1950, 0, 1));
      c.max = iso(aujourdhui());
    }
    bornesCIN();
    if (el('date_naissance')) {
      el('date_naissance').addEventListener('change', function () {
        bornesCIN();
        if (el('Date_CIN') && el('Date_CIN').value) { verifier(el('Date_CIN'), f); }
        if (el('annee_bacc') && el('annee_bacc').value) { verifier(el('annee_bacc'), f); }
      });
    }

    // Contrôle à chaque sortie de champ ; l'erreur disparaît dès que la saisie est corrigée
    Array.prototype.forEach.call(f.elements, function (e) {
      if (!e.name || e.type === 'hidden' || e.type === 'submit') { return; }
      e.addEventListener('blur', function () { verifier(this, f); });
      e.addEventListener('change', function () { verifier(this, f); });
      e.addEventListener('input', function () { if (this.classList.contains('is-invalid')) { verifier(this, f); } });
    });

    // Envoi : tout est revérifié, le premier champ en erreur reçoit le focus
    f.addEventListener('submit', function (ev) {
      var premier = null;
      Array.prototype.forEach.call(f.elements, function (e) {
        if (!e.name || e.type === 'hidden' || e.type === 'submit') { return; }
        if (!verifier(e, f) && !premier) { premier = e; }
      });
      if (premier) {
        ev.preventDefault();
        premier.scrollIntoView({ behavior: 'smooth', block: 'center' });
        premier.focus();
        return;
      }
      var bouton = f.querySelector('[type=submit]');
      if (bouton) { bouton.disabled = true; bouton.value = 'Enregistrement...'; }   // évite le double envoi
    });
  }

  function demarrer() {
    Array.prototype.forEach.call(document.querySelectorAll('form[action="enregistre_candidat.php"]'), installer);
  }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', demarrer); } else { demarrer(); }
})();
