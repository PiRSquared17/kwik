
#cd /tmp
#wget http://downloads.openwrt.org/people/kaloz/nvram-clean.sh
#chmod a+x /tmp/nvram-clean.sh
#/tmp/nvram-clean.sh

empty() {
	case "$1" in
		"") return 0 ;;
		*) return 255 ;;
	esac
}
equal() {
	case "$1" in
		"$2") return 0 ;;
		*) return 255 ;;
	esac
}
neq() {
	case "$1" in
		"$2") return 255 ;;
		*) return 0 ;;
	esac
}
exists() {
	( < $1 ) 2>&-
}

nvram_unset() {
	nvram unset $1
}


echo -n "Before: "
nvram show > /dev/null

nvram show 2>/dev/null | {
	while :; do
		UNSET=""
		
		read LINE
		empty "$LINE" && exit
		CONTENTS="${LINE##*=}"
		LINE="${LINE%%=*}"
		TYPE="${LINE%%_*}"

		case "$TYPE" in
			wan0) UNSET=1;;
			upnp) UNSET=1;;
			forwardspec) UNSET=1;;
			log) UNSET=1;;
			fullswitch) UNSET=1;;
			maskmac) UNSET=1;;
			inet) UNSET=1;;
			wanup) UNSET=1;;
			fon) UNSET=1;;
			filter) UNSET=1;;
			apwatchdog) UNSET=1;;
			routing) UNSET=1;;
			wl) UNSET=1;;
			svqos) UNSET=1;;
			dr) UNSET=1;;
			snmpd) UNSET=1;;
			port) UNSET=1;;
			wshaper) UNSET=1;;
			restore) UNSET=1;;
			qos) UNSET=1;;
			get) UNSET=1;;
			bird) UNSET=1;;
			wol) UNSET=1;;
			expert) UNSET=1;;
			ses) UNSET=1;;
			radvd) UNSET=1;;
			cron) UNSET=1;;
			chilli) UNSET=1;;
			jffs2) UNSET=1;;
			block) UNSET=1;;
			remote) UNSET=1;;
			pptpd) UNSET=1;;
			http) UNSET=1;;
			port) UNSET=1;;
			QoS) UNSET=1;;
			sshd) UNSET=1;;
			ipv6) UNSET=1;;
			syslogd) UNSET=1;;
			tpclient) UNSET=1;;
			ezc) UNSET=1;;
			is) UNSET=1;;
			zebra) UNSET=1;;
			ospfd) UNSET=1;;
			ntp) UNSET=1;;
			mac) UNSET=1;;
			ping) UNSET=1;;
			dnsmasq) UNSET=1;;
			skip) UNSET=1;;
			security) UNSET=1;;
			daylight) UNSET=1;;
			multicast) UNSET=1;;
			aol) UNSET=1;;
			forward) UNSET=1;;
			ct) UNSET=1;;
			sel) UNSET=1;;
			rc) UNSET=1;;
			txpwr) UNSET=1;;
			d11g) UNSET=1;;
			sv) UNSET=1;;
			hs) UNSET=1;;
			https) UNSET=1;;
			mtu) UNSET=1;;
			firmware) UNSET=1;;
			autofw) UNSET=1;;
			version) UNSET=1;;
			tpservers) UNSET=1;;
			stats) UNSET=1;;
			telnetd) UNSET=1;;
			resetbutton) UNSET=1;;
			nas) UNSET=1;;
			httpsd) UNSET=1;;
			def) UNSET=1;;
			fw) UNSET=1;;
			dhcpd) UNSET=1;;
			prange) UNSET=1;;
			manual) UNSET=1;;
			traceroute) UNSET=1;;
			lcp) UNSET=1;;
			time) UNSET=1;;
			local) UNSET=1;;
			wk) UNSET=1;;
			action) UNSET=1;;
			global) UNSET=1;;
			web) UNSET=1;;
			txant) UNSET=1;;
			rate) UNSET=1;;
			timer) UNSET=1;;
			NC) UNSET=1;;
			sipgate) UNSET=1;;
			sh) UNSET=1;;
			ip) UNSET=1;;
			kaid) UNSET=1;;
			sip) UNSET=1;;
			need) UNSET=1;;
			samba) UNSET=1;;
			rflow) UNSET=1;;
			dhcpfwd) UNSET=1;;
			schedule) UNSET=1;;
			max) UNSET=1;;
			sys) UNSET=1;;
			clean) UNSET=1;;
			trunking) UNSET=1;;
			language) UNSET=1;;
			enable) UNSET=1;;
			macupd) UNSET=1;;
			trigger) UNSET=1;;
			vlans) UNSET=1;;
			smtp) UNSET=1;;
			status) UNSET=1;;
			mmc) UNSET=1;;
		esac
	
		TYPE="${TYPE%%[0-9]}"
		TYPE="${TYPE%%[0-9]}"

		case "$TYPE" in
			altdns) UNSET=1;;
		esac
		
		TAIL=${LINE##*_}
		case "$TAIL" in
			static) UNSET=1;;
			statics) UNSET=1;;
			ospf) UNSET=1;;
			if) UNSET=1;;
			desc) UNSET=1;;
			buf) UNSET=1;;
			macmode1) UNSET=1;;
			lease) UNSET=1;;
			primary) UNSET=1;;
			speed) UNSET=1;;
			passphrase) UNSET=1;;
			pass) UNSET=1;;
			dyndnstype) UNSET=1;;
			domain) UNSET=1;;
			srv) UNSET=1;;
			unit) UNSET=1;;
			2) UNSET=1;;
			route) UNSET=1;;
			pppifname) UNSET=1;;
			ac) UNSET=1;;
			dns0|dns1|dns2) UNSET=1;;
			dhcp) UNSET=1;;
			wins) UNSET=1;;
			ofdm) UNSET=1;;
			result) UNSET=1;;
			loglevel) UNSET=1;;
			default) UNSET=1;;
			change) UNSET=1;;
			disable) UNSET=1;;
			keepalive) UNSET=1;;
			accepted) UNSET=1;;
			rejected) UNSET=1;;
			dropped) UNSET=1;;
			server) UNSET=1;;
			delay) UNSET=1;;
			hwnames) UNSET=1;;
			nat) UNSET=1;;
			style) UNSET=1;;
			encrypt) UNSET=1;;
			status) UNSET=1;;
			dnsmasq) UNSET=1;;
			mac) UNSET=1;;
			service) UNSET=1;;
			x) UNSET=1;;
			t) UNSET=1;;
			enable)
				case "$TYPE" in
					ddns);;
					log);;
					upnp);;
					*) UNSET=1;;
				esac
			;;
		esac
		
		TAIL1="$TAIL"
		TAIL="${TAIL%%[0-9]}"
		TAIL="${TAIL%%[0-9]}"

		case "$TAIL" in
			wds) equal "$TAIL" "$TAIL1" || UNSET=1;;
			ipaddr|netmask) equal "$TYPE" wl && UNSET=1;;
			hwaddr)
				TMP=${LINE%[0-9]*}
				TMP=${TMP%[0-9]}
				equal "$TMP" "wl0_wds" && UNSET=1
			;;
		esac

		case "$LINE" in
			pppoe_ver) UNSET=1;;
			wl0_lazy_wds) UNSET=1;;
			pppoe_static_ip) UNSET=1;;
			ppp_static_ip) UNSET=1;;
			pptp_get_ip) UNSET=1;;
			l2tp_get_ip) UNSET=1;;
			wan_get_dns) UNSET=1;;
			wan_run_mtu) UNSET=1;;
			ddns_wildcard) UNSET=1;;
			os_name) UNSET=1;;
		esac

		case "$CONTENTS" in
			11111) UNSET=1;;
			0) equal "$TAIL" ipaddr && UNSET=1;;
			"") UNSET=1;;
		esac
	
		case "${TYPE%%[0-9]*}" in
			port) UNSET=1;;
		esac
	
		equal "$UNSET" 1 && nvram_unset "$LINE"
	done
}
echo -n "After: "
nvram show >/dev/null
