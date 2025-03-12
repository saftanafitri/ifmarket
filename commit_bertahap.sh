#!/bin/bash

# Pastikan sedang berada di branch yang benar
BRANCH=$(git branch --show-current)
echo "Saat ini berada di branch: $BRANCH"

# Dapatkan daftar folder di root project (kecuali .git)
folders=$(find . -maxdepth 1 -type d ! -name ".git" ! -name "." | sed 's|^\./||')

# Loop untuk setiap folder di root
for folder in $folders; do
    echo "-----------------------------------"
    echo "Folder: $folder"
    read -p "Commit seluruh folder ini? (y/n): " confirm_folder

    if [ "$confirm_folder" = "y" ]; then
        git add "$folder" || echo "❌ Gagal menambahkan $folder"
        read -p "Masukkan pesan commit untuk $folder: " msg
        msg=${msg:-"Initial Commit"} # Jika kosong, gunakan default
        git commit -m "$msg" || echo "❌ Gagal commit $folder"
    else
        # Cek apakah ada subfolder di dalam folder ini
        subfolders=$(find "$folder" -mindepth 1 -maxdepth 1 -type d)

        if [ -n "$subfolders" ]; then
            echo "📂 Folder $folder memiliki subfolder:"
            echo "$subfolders"
            read -p "Commit per folder dalam folder ini? (y/n): " confirm_subfolder

            if [ "$confirm_subfolder" = "y" ]; then
                for subfolder in $subfolders; do
                    echo "-----------------------------------"
                    echo "Subfolder: $subfolder"
                    read -p "Commit seluruh subfolder ini? (y/n): " confirm_subfolder_commit

                    if [ "$confirm_subfolder_commit" = "y" ]; then
                        git add "$subfolder" || echo "❌ Gagal menambahkan $subfolder"
                        read -p "Masukkan pesan commit untuk $subfolder: " msg
                        msg=${msg:-"Initial Commit"} # Jika kosong, gunakan default
                        git commit -m "$msg" || echo "❌ Gagal commit $subfolder"
                    else
                        echo "-> Commit per file di subfolder $subfolder"
                        files=$(find "$subfolder" -type f)

                        for file in $files; do
                            read -p "Commit file $file? (y/n): " confirm_file
                            if [ "$confirm_file" = "y" ]; then
                                git add "$file" || echo "❌ Gagal menambahkan $file"
                                read -p "Masukkan pesan commit untuk file $file: " msg
                                msg=${msg:-"Initial Commit"} # Jika kosong, gunakan default
                                git commit -m "$msg" || echo "❌ Gagal commit $file"
                            fi
                        done
                    fi
                done
            else
                echo "-> Commit per file di folder $folder"
                files=$(find "$folder" -type f)

                for file in $files; do
                    read -p "Commit file $file? (y/n): " confirm_file
                    if [ "$confirm_file" = "y" ]; then
                        git add "$file" || echo "❌ Gagal menambahkan $file"
                        read -p "Masukkan pesan commit untuk file $file: " msg
                        msg=${msg:-"Initial Commit"} # Jika kosong, gunakan default
                        git commit -m "$msg" || echo "❌ Gagal commit $file"
                    fi
                done
            fi
        else
            echo "-> Commit per file di folder $folder"
            files=$(find "$folder" -type f)

            for file in $files; do
                read -p "Commit file $file? (y/n): " confirm_file
                if [ "$confirm_file" = "y" ]; then
                    git add "$file" || echo "❌ Gagal menambahkan $file"
                    read -p "Masukkan pesan commit untuk file $file: " msg
                    msg=${msg:-"Initial Commit"} # Jika kosong, gunakan default
                    git commit -m "$msg" || echo "❌ Gagal commit $file"
                fi
            done
        fi
    fi
done

echo "-----------------------------------"
echo "Semua perubahan sudah di-commit!"
read -p "Push ke remote sekarang? (y/n): " confirm_push
if [ "$confirm_push" = "y" ]; then
    git push -u lokal $BRANCH || echo "❌ Gagal push ke remote"
fi