#!/usr/bin/env bash

VERSION="v0.2.0"

aptitude -q -y install daemon

echo "Installing MailHog ${VERSION}..."

mkdir -p /opt/mailhog
chown mail:mail /opt/mailhog
wget -q -O /opt/mailhog/mailhog https://github.com/mailhog/MailHog/releases/download/${VERSION}/MailHog_linux_amd64
chmod +x /opt/mailhog/mailhog

cat > /etc/php5/conf.d/30-mailhog.ini <<'EOF'
; Mailhog replacement for sendmail -t -i
sendmail_path = "/opt/mailhog/mailhog sendmail"
EOF

cat > /etc/init.d/mailhog <<'EOF'
#! /bin/sh
# /etc/init.d/mailhog
#
# MailHog init script.
#
# @author Jeff Geerling

### BEGIN INIT INFO
# Provides:          mailhog
# Required-Start:    $remote_fs $syslog
# Required-Stop:     $remote_fs $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Start MailHog at boot time.
# Description:       Enable MailHog.
### END INIT INFO

PID=/opt/mailhog/mailhog.pid
LOG=/opt/mailhog/mailhog.log
USER=mail
GROUP=mail
BIN=/opt/mailhog/mailhog
DAEMON=daemon

# Carry out specific functions when asked to by the system
case "$1" in
  start)
    echo "Starting mailhog."
    $DAEMON -n mailhog -F $PID -u $USER:$GROUP -- $BIN
    ;;
  stop)
    $DAEMON -n mailhog -F $PID --running
    if [ $? = 0 ]; then
      echo "Stopping MailHog.";
      $DAEMON -v -n mailhog -F $PID --stop
    else
      echo "MailHog is not running.";
    fi
    ;;
  restart)
    $DAEMON -n mailhog -F $PID --running
    if [ $? = 0 ]; then
        echo "Restarting mailhog."
        $DAEMON -n mailhog -F $PID --restart
        if [ $? = 0 ]; then
          echo "MailHog restarted successfully"
        else
          echo "Error while restarting MailHog"
        fi
    else
      echo "MailHog is not running."
    fi
    ;;
  status)
    $DAEMON -v -n mailhog -F $PID --running
    ;;
  *)
    echo "Usage: /etc/init.d/mailhog {start|stop|status|restart}"
    exit 1
    ;;
esac

exit 0
EOF
chmod +x /etc/init.d/mailhog
update-rc.d mailhog defaults
service mailhog start
