{{-- Primary nav when a doctor is signed in on /doctor-portal/* — avoids CMS "Reports" pointing at patient report lookup. --}}
<a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
<a href="{{ route('doctor.portal.dashboard') }}" class="{{ request()->routeIs('doctor.portal.dashboard') ? 'active' : '' }}">Overview</a>
<a href="{{ route('doctor.portal.referrals') }}" class="{{ request()->routeIs('doctor.portal.referrals') ? 'active' : '' }}">Referrals</a>
<a href="{{ route('doctor.portal.commissions') }}" class="{{ request()->routeIs('doctor.portal.commissions') ? 'active' : '' }}">Commissions</a>
<a href="{{ route('doctor.portal.reports') }}" class="{{ request()->routeIs('doctor.portal.reports') ? 'active' : '' }}">My reports</a>
<a href="{{ route('public.appointments.create') }}" class="nav-cta">Book appointment</a>
