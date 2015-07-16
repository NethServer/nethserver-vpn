Summary: NethServer vpn configuration
Name: nethserver-vpn
Version: 1.1.6
Release: 1%{?dist}
License: GPL
URL: %{url_prefix}/%{name} 
Source0: %{name}-%{version}.tar.gz
BuildArch: noarch

Requires: nethserver-directory

BuildRequires: perl
BuildRequires: nethserver-devtools 

%description
NethServer vpn configuration

%prep
%setup

%build
%{makedocs}
perl createlinks

%install
rm -rf $RPM_BUILD_ROOT
(cd root; find . -depth -print | cpio -dump $RPM_BUILD_ROOT)
%{genfilelist} $RPM_BUILD_ROOT --dir /var/lib/nethserver/certs/clients 'attr(0740,srvmgr,adm)' > %{name}-%{version}-filelist
echo "%doc COPYING" >> %{name}-%{version}-filelist

%post

%preun

%files -f %{name}-%{version}-filelist
%defattr(-,root,root)

%changelog
* Thu Jul 16 2015 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.6-1
- IPsec tunnels (net2net) web interface - Feature #3194 [NethServer]

* Wed Mar 11 2015 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.5-1
- missing vpn certs in configuration backup - Enhancement #3071 [NethServer]

* Fri Dec 12 2014 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.4-1.ns6
- Creating a vpn user corrupts the system user with the same name - Bug #2974 [NethServer]

* Thu Nov 27 2014 Davide Principi <davide.principi@nethesis.it> - 1.1.3-1.ns6
- Permission denied when creating VPN users - Bug #2965 [NethServer]

* Wed Feb 26 2014 Davide Principi <davide.principi@nethesis.it> - 1.1.2-1.ns6
- Revamp web UI style - Enhancement #2656 [NethServer]

* Wed Feb 05 2014 Davide Principi <davide.principi@nethesis.it> - 1.1.1-1.ns6
- NethCamp 2014 - Task #2618 [NethServer]
- Move admin user in LDAP DB - Feature #2492 [NethServer]
- Dashboard:  OpenVPN status widget - Enhancement #2300 [NethServer]
- Update all inline help documentation - Task #1780 [NethServer]

* Wed Oct 23 2013 Davide Principi <davide.principi@nethesis.it> - 1.1.0-1.ns6
- VPN: add support for OpenVPN net2net - Feature #1958 [NethServer]
- VPN: support IPsec/L2TP - Feature #1957 [NethServer]
- VPN: support for OpenVPN roadwarrior - Feature #1956 [NethServer]
- VPN - Feature #1763 [NethServer]

* Thu Aug 29 2013 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.0-1.ns6
- First release #1763


