import os
import zipfile
import sys

# Ensure UTF-8 output on Windows terminal
if sys.platform == 'win32':
    sys.stdout.reconfigure(encoding='utf-8')

def zip_folder(source_folder, output_zip_path, base_internal_folder=None):
    os.makedirs(os.path.dirname(output_zip_path), exist_ok=True)
    with zipfile.ZipFile(output_zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(source_folder):
            for file in files:
                file_path = os.path.join(root, file)
                rel_path = os.path.relpath(file_path, source_folder)
                if base_internal_folder:
                    archive_name = os.path.join(base_internal_folder, rel_path)
                else:
                    archive_name = rel_path
                zipf.write(file_path, archive_name)
    print(f"[OK] Berhasil membuat zip: {output_zip_path}")

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    dist_dir = os.path.join(base_dir, "dist")
    os.makedirs(dist_dir, exist_ok=True)

    # 1. Package WordPress Plugin
    plugin_src = os.path.join(base_dir, "plugins", "haohao-qris-payment")
    plugin_zip = os.path.join(dist_dir, "haohao-qris-payment.zip")
    zip_folder(plugin_src, plugin_zip, base_internal_folder="haohao-qris-payment")

    # 2. Package WordPress Theme
    theme_src = os.path.join(base_dir, "themes", "haohao-mandarin-theme")
    theme_zip = os.path.join(dist_dir, "haohao-mandarin-theme.zip")
    zip_folder(theme_src, theme_zip, base_internal_folder="haohao-mandarin-theme")

    print("\n[OK] Seluruh package WordPress (.zip) siap di-upload ke WordPress asli!")

if __name__ == "__main__":
    main()
