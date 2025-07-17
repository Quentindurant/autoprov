import os
import sys
import json

def validate_param(name, value, required=True):
    if required and (value is None or value == ""):
        raise ValueError(f"Le paramètre {name} est obligatoire.")
    return value

def generate_ini_config(
    mac,
    label,
    display_name,
    user_name,
    auth_name,
    password,
    sip_server,
    sip_port,
    lang_gui="French",
    lang_wui="French",
    admin_password="UGCI8376",
    autoprov_url="https://autoprov.quentindurant.com/",
    linekeys=None,
    blfs=None,
    logo_mode=2,
    timezone='+1',
    timezone_name='France(Paris)',
    headset_mode=1,
    transfer_type=1
):
    """
    Génère un fichier INI de provisioning Yealink complet et le .boot associé.
    """
    mac_clean = mac.lower().replace(':', '')
    ini_filename = f"{mac_clean}.cfg"
    boot_filename = f"{mac_clean}.boot"

    # Validation de base
    validate_param("mac", mac)
    validate_param("label", label)
    validate_param("display_name", display_name)
    validate_param("user_name", user_name)
    validate_param("auth_name", auth_name)
    validate_param("password", password)
    validate_param("sip_server", sip_server)
    validate_param("sip_port", sip_port)

    # Construction du contenu INI principal
    ini_lines = [
        '#!version:1.0.0.1',
        f'account.1.enable = 1',
        f'account.1.label = {label}',
        f'account.1.display_name = {display_name}',
        f'account.1.user_name = {user_name}',
        f'account.1.auth_name = {auth_name}',
        f'account.1.password = {password}',
        f'account.1.sip_server.1.address = {sip_server}',
        f'account.1.sip_server.1.port = {sip_port}',
        f'lang.gui = {lang_gui}',
        f'lang.wui = {lang_wui}',
    ]

    # Touches écran (linekeys)
    if linekeys:
        for idx, key in enumerate(linekeys, start=1):
            ini_lines.append(f'linekey.{idx}.label = {key.get("label", "Ligne "+str(idx))}')
            ini_lines.append(f'linekey.{idx}.line = {key.get("line", 1)}')
            ini_lines.append(f'linekey.{idx}.value = {key.get("value", idx)}')

    # Touches BLF
    if blfs:
        for idx, blf in enumerate(blfs, start=1):
            i = idx + (len(linekeys) if linekeys else 1)
            ini_lines.append(f'linekey.{i}.label = {blf.get("label", "BLF"+str(idx))}')
            ini_lines.append(f'linekey.{i}.line = {blf.get("line", 1)}')
            ini_lines.append(f'linekey.{i}.type = 16')
            if "extension" in blf:
                ini_lines.append(f'linekey.{i}.extension = {blf["extension"]}')
            if "value" in blf:
                ini_lines.append(f'linekey.{i}.value = {blf["value"]}')

    # Heure/Timezone
    ini_lines += [
        'local_time.dhcp_time = 1',
        f'local_time.time_zone = {timezone}',
        f'local_time.time_zone_name = {timezone_name}',
    ]

    # Casque, transfert, sécurité, logo
    ini_lines += [
        f'phone_setting.custom_headset_mode_status = {headset_mode}',
        f'transfer.dsskey_deal_type = {transfer_type}',
        f'static.security.user_password = admin:{admin_password}',
        f'phone_setting.lcd_logo.mode = {logo_mode}',
    ]

    # Provisioning
    ini_lines += [
        'static.auto_provision.custom.protect = 0',
        'static.auto_provision.custom.sync = 0',
        f'static.auto_provision.server.url = {autoprov_url}',
        'static.auto_provision.server.username = admin',
        f'static.auto_provision.server.password = {admin_password}',
        'static.auto_provision.repeat.enable = 1',
        'static.auto_provision.repeat.minute = 0.5',
    ]

    # Écriture du .cfg
    with open(ini_filename, 'w', encoding='utf-8', newline='\n') as f:
        f.write('\n'.join(ini_lines) + '\n')
    print(f"Fichier {ini_filename} généré avec succès ! (INI)")

    # Génération du .boot
    boot_content = f"""#!version:1.0.0.1\ninclude:config <{ini_filename}>\ninclude:config \"{ini_filename}\"\noverwrite_mode=1\nspecific_model.excluded_mode=0\n"""
    with open(boot_filename, 'w', encoding='utf-8', newline='\n') as f:
        f.write(boot_content)
    print(f"Fichier {boot_filename} généré avec succès ! (.boot)")

if __name__ == "__main__":
    # Exemple d'appel CLI : python3 generate_config.py '{json}'
    if len(sys.argv) == 2:
        params = json.loads(sys.argv[1])
        generate_ini_config(**params)
    else:
        print("Usage: python3 generate_config.py '{json_params}'")
