#!/usr/bin/env bash

set -euo pipefail

branch="$(git branch --show-current)"

if [ -z "$branch" ]; then
    echo "Error: no se pudo detectar la rama actual."
    exit 1
fi

if [ "$branch" = "main" ] || [ "$branch" = "develop" ]; then
    echo "Error: no se permiten pushes directos desde $branch."
    exit 1
fi

if ! git diff --cached --quiet; then
    echo "Error: hay cambios preparados en staging. Revísalos antes de continuar."
    exit 1
fi

echo "Ejecutando build de Vite..."
npm run build

commit_group() {
    local message="$1"
    shift

    git add -A -- "$@"

    if git diff --cached --quiet; then
        echo "Sin cambios para: $message"
        return
    fi

    git commit -m "$message"
}

commit_group \
    "chore(assets): centraliza configuracion Tailwind en Vite" \
    "vite.config.js" \
    "resources/css/tribunal.css" \
    "resources/js/tailwind-config.js" \
    "public/js/admin-config.js" \
    "resources/views/layouts/admin.blade.php" \
    "resources/views/layouts/tribunal.blade.php" \
    "resources/views/layouts/jrv.blade.php"

commit_group \
    "feat(auth): mejora validacion y seguridad de autenticacion" \
    "assets/js/login.js" \
    "resources/views/auth/login.blade.php" \
    "resources/views/layouts/voting.blade.php"

commit_group \
    "refactor(ui): agrega componentes Blade reutilizables" \
    "resources/views/components/stat-card.blade.php" \
    "resources/views/components/form-field.blade.php" \
    "resources/views/components/delete-form.blade.php"

commit_group \
    "refactor(mvc): mueve consulta de mesas al controlador" \
    "app/Http/Controllers/UsuarioController.php" \
    "resources/views/admin/usuarios_edit.blade.php"

commit_group \
    "docs: actualiza README del proyecto" \
    "README.md"

echo "Commits creados:"
git log --oneline -5

echo "Subiendo la rama $branch..."
git push -u origin "$branch"

echo "Push completado correctamente."
