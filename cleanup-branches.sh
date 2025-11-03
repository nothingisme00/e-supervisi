#!/bin/bash

# Script untuk membersihkan branch-branch yang tidak diperlukan
# Script ini akan menghapus branch develop, master, dan feature/crud-admin

set -e  # Exit on error

echo "=================================="
echo "Branch Cleanup Script"
echo "=================================="
echo ""

# Konfirmasi dari user
echo "Script ini akan menghapus branch berikut dari remote:"
echo "  - develop"
echo "  - master"
echo "  - feature/crud-admin"
echo "  - copilot/merge-commits-to-main"
echo ""
read -p "Apakah Anda yakin ingin melanjutkan? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "Dibatalkan oleh user."
    exit 0
fi

echo ""
echo "Mulai menghapus branch remote..."
echo ""

# Hapus branch develop
echo "Menghapus branch 'develop'..."
if git push origin --delete develop 2>/dev/null; then
    echo "✓ Branch 'develop' berhasil dihapus"
else
    echo "⚠ Branch 'develop' mungkin sudah dihapus atau tidak ada"
fi

# Hapus branch master
echo "Menghapus branch 'master'..."
if git push origin --delete master 2>/dev/null; then
    echo "✓ Branch 'master' berhasil dihapus"
else
    echo "⚠ Branch 'master' mungkin sudah dihapus atau tidak ada"
fi

# Hapus branch feature/crud-admin
echo "Menghapus branch 'feature/crud-admin'..."
if git push origin --delete feature/crud-admin 2>/dev/null; then
    echo "✓ Branch 'feature/crud-admin' berhasil dihapus"
else
    echo "⚠ Branch 'feature/crud-admin' mungkin sudah dihapus atau tidak ada"
fi

# Hapus branch copilot/merge-commits-to-main
echo "Menghapus branch 'copilot/merge-commits-to-main'..."
if git push origin --delete copilot/merge-commits-to-main 2>/dev/null; then
    echo "✓ Branch 'copilot/merge-commits-to-main' berhasil dihapus"
else
    echo "⚠ Branch 'copilot/merge-commits-to-main' mungkin sudah dihapus atau tidak ada"
fi

echo ""
echo "=================================="
echo "Membersihkan branch local..."
echo "=================================="
echo ""

# Pastikan kita di branch main
git checkout main 2>/dev/null || echo "⚠ Sudah di branch main atau branch main tidak ada"

# Hapus branch local
for branch in develop master feature/crud-admin copilot/merge-commits-to-main; do
    echo "Menghapus local branch '$branch'..."
    if git branch -D "$branch" 2>/dev/null; then
        echo "✓ Local branch '$branch' berhasil dihapus"
    else
        echo "⚠ Local branch '$branch' mungkin sudah dihapus atau tidak ada"
    fi
done

echo ""
echo "=================================="
echo "Branch yang tersisa:"
echo "=================================="
git branch -a

echo ""
echo "=================================="
echo "Selesai!"
echo "=================================="
echo ""
echo "Branch 'main' sekarang menjadi satu-satunya branch aktif."
echo "Semua commit masa depan harus ke branch 'main'."
