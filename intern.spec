%define name intern
%define release 1
%define install_dir /var/www/html/hub/mod/intern

Summary:   Internship Inventory
Name:      %{name}
Version:   %{version}
Release:   %{release}
License:   GPL
Group:     Development/PHP
URL:       http://phpwebsite.appstate.edu
Source:    %{name}-%{version}.tar.bz2
Requires:  php >= 5.0.0, php-gd >= 5.0.0, phpwebsite
BuildArch: noarch

%description
The Internship Inventory

%prep
%setup -n intern

%post
curl http://127.0.0.1/apc/clear/

%install
mkdir -p "$RPM_BUILD_ROOT%{install_dir}"
cp -r * $RPM_BUILD_ROOT%{install_dir}

%clean
rm -rf "$RPM_BUILD_ROOT%install_dir"

%files
%defattr(-,apache,apache)
%{install_dir}

%changelog
* Fri May 11 2012 Jeff Tickle <jtickle@tux.appstate.edu>
- Initial RPM for Internship Inventory
