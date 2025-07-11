from jinja2 import Template

config_template = """<?xml version="1.0" encoding="UTF-8"?>
<YealinkIPPhoneConfiguration Beep="no">
    <account.1.enable>1</account.1.enable>
    <account.1.label>{{ extension }}</account.1.label>
    <account.1.display_name>{{ username }}</account.1.display_name>
    <account.1.user_name>{{ extension }}</account.1.user_name>
    <account.1.auth_name>{{ extension }}</account.1.auth_name>
    <account.1.password>{{ password }}</account.1.password>
    <account.1.sip_server.1.address>{{ sip_server }}</account.1.sip_server.1.address>
</YealinkIPPhoneConfiguration>"""

def generate_config(mac, username, extension, password, sip_server):
    template = Template(config_template)
    config_data = template.render(
        username=username,
        extension=extension,
        password=password,
        sip_server=sip_server
    )

    filename = f"{mac.lower().replace(':', '')}.cfg"
    # Génération du XML
    with open(filename, 'w', encoding='utf-8') as file:
        file.write(config_data)
    print(f"Fichier {filename} généré avec suc ! (XML)")

    # Génération du INI
    ini_content = f"""
account.1.enable = 1
account.1.label = {extension}
account.1.display_name = {username}
account.1.user_name = {extension}
account.1.auth_name = {extension}
account.1.password = {password}
account.1.sip_server.1.address = {sip_server}
""".strip()
    ini_filename = f"{mac.lower().replace(':', '')}_ini.cfg"
    with open(ini_filename, 'w', encoding='utf-8') as file:
        file.write(ini_content)
    print(f"Fichier {ini_filename} généré avec succèsssssss YAAAAAAAAAAAAAAA! (INI)")

if __name__ == "__main__":
    import sys
    if len(sys.argv) == 6:
        _, mac, username, extension, password, sip_server = sys.argv
        generate_config(mac, username, extension, password, sip_server)
    else:
        print("Usage: python3 generate_config.py <mac> <username> <extension> <password> <sip_server>")

