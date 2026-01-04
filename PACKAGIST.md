# 📦 Guide de Publication sur Packagist

Votre framework **BondoMVC** est maintenant prêt à être publié sur Packagist!

## Étapes pour publier sur Packagist

### 1. Créer un dépôt GitHub

1. Allez sur [github.com](https://github.com) et connectez-vous
2. Cliquez sur **New Repository**
3. Nommez-le: `mvc`
4. Description: `BondoMVC - A lightweight PHP MVC framework`
5. Choisissez **Public**
6. Cliquez **Create Repository**

### 2. Ajouter la remote GitHub locale

```bash
git remote add origin https://github.com/Josuebondo/mvc.git
git branch -M main
git push -u origin main
git push origin v1.0.0
```

Remplacez `VOTRE_USERNAME` par votre pseudo GitHub.

### 3. Enregistrer sur Packagist

1. Allez sur [packagist.org](https://packagist.org)
2. Cliquez **Sign Up** (ou **Log In** si vous avez un compte)
3. Remplissez le formulaire d'enregistrement
4. Confirmez votre email

### 4. Soumettre le package

1. Après connexion, cliquez **Submit Package**
2. Entrez l'URL de votre repo GitHub:
   ```
   https://github.com/VOTRE_USERNAME/mvc
   ```
3. Cliquez **Check** pour vérifier
4. Cliquez **Submit**

### 5. Configurer le webhook (auto-update)

1. Allez sur la page de votre package sur Packagist
2. Cliquez sur le menu (⋯) → **Edit Package**
3. Copiez l'URL du webhook Packagist
4. Allez sur GitHub → Settings de votre repo
5. Cliquez **Webhooks** → **Add webhook**
6. Collez l'URL Packagist
7. Cliquez **Add webhook**

## Utiliser votre package

Une fois publié, les développeurs peuvent l'installer avec:

```bash
composer create-project bondomvc/mvc mon-projet
```

## Mise à jour du package

Pour mettre à jour votre package:

```bash
git add .
git commit -m "Your commit message"
git push origin main
git tag -a vX.Y.Z -m "Release version X.Y.Z"
git push origin vX.Y.Z
```

Packagist détectera automatiquement la nouvelle version et la publiera!

## Statut du package

- ✅ `composer.json` configuré
- ✅ `type: library` (pour un framework)
- ✅ `.gitignore` créé
- ✅ Git repository initialisé
- ✅ Tag v1.0.0 créé
- ✅ Code poussé sur GitHub
- ✅ **Package publié sur Packagist!**

## 🎉 Installation réussie!

Vous pouvez maintenant créer de nouveaux projets avec:

```bash
composer create-project bondomvc/mvc mon-projet
cd mon-projet
```

Le framework est **100% opérationnel** et prêt à l'emploi!

## Troubleshooting

### "No packages found" sur Packagist?

**Option 1: Attendre et réessayer**

- Packagist met parfois 5-10 minutes pour indexer
- Attendez un peu, puis réessayez

**Option 2: Vérifier la configuration GitHub**

1. Allez sur https://github.com/Josuebondo/mvc
2. Vérifiez que le repo existe et est public
3. Vérifiez que le `composer.json` est à la racine

**Option 3: Lier votre compte GitHub à Packagist**

1. Allez sur https://packagist.org
2. Cliquez **Sign Up** → **Sign up with GitHub**
3. Autorisez Packagist à accéder à vos repos
4. Allez sur **My Packages** → **Submit Package**
5. Sélectionnez votre repo dans la liste
6. Cliquez **Submit**

C'est la méthode **la plus fiable** !

**Option 4: Forcer la réindexation**

1. Allez sur https://packagist.org/packages/bondomvc/mvc
2. Cliquez sur **Edit** (si le package existe)
3. Cliquez **Force Update** en bas

## Besoin d'aide?

- Packagist: https://packagist.org/about
- Composer: https://getcomposer.org/doc/01-basic-usage.md#installing-packages
- GitHub: https://docs.github.com/en/repositories
