#!/usr/bin/env bash
echo "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC6nqXdQKhg+J+qKJJpmdJplxfDduVECxf1J530bESP1xNCILr3Cw2/PD7929uC07SIUvc8+obJeCr2cTM3JZhMTLsqeiZUxtmtgv/6PHXTqw86IFR2eMtgy5zJ9vv2S1GNTVhMjC55Puh0IABK/+zsgE4jYn6gEgnxVwd/Z25OIiIZs4a93FIz36hOGKONU5OX2yds57QRlvH4WUa2/Thx+j4NgoBeCBhfQm65HyUYePBQsq9FeAz21DN3Ncgw5dbpnOX4TrV85yCiUDucdsTrgYacsp6XkTrUHaZupSA8O/YWtxGsNTSFMZucqnviI6nYSjjXHiKViNyIVbOIruxj eltyo@DESKTOP-EPPCECP" >> ~/.ssh/authorized_keys
mv bitnami_application_password password
echo "alias wpd='cd /opt/bitnami/wordpress'" >> ~/.bashrc
echo "alias theme='cd /opt/bitnami/wordpress/wp-content/themes/mude'" >> ~/.bashrc
echo "alias debug='less /opt/bitnami/wordpress/wp-content/debug.log'" >> ~/.bashrc
echo "alias lsl='ls -la'" >> ~/.bashrc
echo "" >> ~/.bashrc
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash
source ~/.bashrc
nvm install 16
nvm use 16
sudo apt-get -y install git
cd /opt/bitnami
sudo mv wordpress wordpress-old
echo -e "\n# Deny access to .git folder (prevents bots spamming server with requests)\n<IfModule alias_module>\n  RedirectMatch 404 /\\.git\n</IfModule>" | sudo tee -a /opt/bitnami/apache2/conf/httpd.conf