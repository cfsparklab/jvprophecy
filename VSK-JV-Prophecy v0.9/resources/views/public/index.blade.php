@extends('layouts.app')

@section('title', 'Jebikalam Vaanga Prophecy - Home')

@section('content')
<div style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%); position: relative; overflow-x: hidden;">
    
    <!-- Background Pattern -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.03; background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23000000" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></g></svg>'); z-index: 0;"></div>
    
    <!-- Professional Header -->
    <header style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px); box-shadow: 0 4px 32px rgba(0, 0, 0, 0.08); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(59, 130, 246, 0.1);">
        <div class="intel-container" style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0;">
            <!-- Premium Logo Section -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 22px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-dove" style="color: white; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <h1 style="font-size: 1.4rem; font-weight: 900; margin: 0; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: -0.025em;">
                        Jebikalam Vaanga Prophecy
                    </h1>
                    <p style="font-size: 0.6rem; color: #64748b; margin: 0; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;">
                        Secure • Multi-Language • Spiritual
                    </p>
                </div>
            </div>
            
            <!-- Premium User Menu -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                @auth
                    <!-- Executive User Profile -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.8)); padding: 0.75rem 1.25rem; border-radius: 16px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05); border: 1px solid rgba(59, 130, 246, 0.1);">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" style="width: 44px; height: 44px; border-radius: 12px; border: 2px solid rgba(59, 130, 246, 0.2); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        @else
                            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1rem; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p style="font-size: 0.9rem; font-weight: 700; color: #1e293b; margin: 0; line-height: 1.2;">{{ auth()->user()->name }}</p>
                            <p style="font-size: 0.75rem; color: #64748b; margin: 0; font-weight: 500;">Super Administrator</p>
                        </div>
                    </div>
                    
                    <!-- Executive Action Buttons -->
                    <div style="display: flex; gap: 0.75rem;">
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor']))
                        <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); color: white; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; text-decoration: none; box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: none; cursor: pointer;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(124, 58, 237, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(124, 58, 237, 0.3)';">
                            <i class="fas fa-cog"></i>
                            <span>Admin</span>
                        </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #64748b 0%, #475569 100%); color: white; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; box-shadow: 0 4px 16px rgba(100, 116, 139, 0.3); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: none; cursor: pointer;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(100, 116, 139, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(100, 116, 139, 0.3)';">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div style="display: flex; gap: 0.75rem;">
                        <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255, 255, 255, 0.9); color: #1e293b; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; text-decoration: none; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(0, 0, 0, 0.05)';">
                            <i class="fas fa-sign-in-alt"></i>
                            Login
                        </a>
                        <a href="{{ route('register') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; text-decoration: none; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(59, 130, 246, 0.3)';">
                            <i class="fas fa-user-plus"></i>
                            Sign Up
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Executive Hero Section -->
    <section style="padding: 2rem 0; text-align: center; position: relative; z-index: 1;">
        <div class="intel-container">
            @auth
                <!-- Executive Welcome -->
                <div style="max-width: 900px; margin: 0 auto;">
                    <div style="width: 67px; height: 67px; background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 50%, #4c1d95 100%); border-radius: 17px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.8rem; box-shadow: 0 8px 28px rgba(124, 58, 237, 0.3);">
                        <i class="fas fa-praying-hands" style="color: white; font-size: 1.75rem;"></i>
                    </div>
                    <h2 style="font-size: 2.45rem; font-weight: 900; color: #0f172a; margin-bottom: 0.6rem; line-height: 1.1; letter-spacing: -0.025em;">
                        Welcome back,<br>
                        <span style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ auth()->user()->name }}</span><span style="font-size: 0.7em; margin-left: 0.5rem;">🙏</span>
                    </h2>
                    <p style="font-size: 0.96rem; color: #475569; margin-bottom: 1.2rem; line-height: 1.6; font-weight: 500; max-width: 700px; margin-left: auto; margin-right: auto;">
                        Ready to Pray? Select a Jebikalam Vaanga Telecast date to view and download prophecies in your preferred language.
                    </p>
                </div>

            @else
                <!-- Executive Guest Experience -->
                <div style="max-width: 900px; margin: 0 auto;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%); border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem; box-shadow: 0 16px 48px rgba(59, 130, 246, 0.3);">
                        <i class="fas fa-dove" style="color: white; font-size: 3rem;"></i>
                    </div>
                    <h2 style="font-size: 4rem; font-weight: 900; color: #0f172a; margin-bottom: 0.8rem; line-height: 1.1; letter-spacing: -0.025em;">
                        <span style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Prophecies</span><br>
                        Await You <span style="font-size: 0.7em;">✨</span>
                    </h2>
                    <p style="font-size: 1.375rem; color: #475569; margin-bottom: 1.5rem; line-height: 1.6; font-weight: 500; max-width: 700px; margin-left: auto; margin-right: auto;">
                        Experience divine guidance through carefully curated prophecies. Select specific Jebikalam Vaanga Telecast dates and access spiritual content in your preferred language.
                    </p>
                    
                    <!-- Executive CTA Section -->
                    <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center; margin-bottom: 3rem;">
                        <a href="{{ route('register') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 1.25rem 2.5rem; border-radius: 16px; font-weight: 700; font-size: 1.125rem; text-decoration: none; box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 16px 48px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px rgba(59, 130, 246, 0.3)';">
                            <i class="fas fa-user-plus"></i>
                            Start Your Spiritual Journey
                        </a>
                        <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; background: rgba(255, 255, 255, 0.9); color: #1e293b; padding: 1.25rem 2.5rem; border-radius: 16px; font-weight: 700; font-size: 1.125rem; text-decoration: none; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08); border: 1px solid rgba(59, 130, 246, 0.2); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 16px 48px rgba(0, 0, 0, 0.12)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 32px rgba(0, 0, 0, 0.08)';">
                            <i class="fas fa-sign-in-alt"></i>
                            I'm Already a Member
                        </a>
                    </div>
                    
                    <div style="display: inline-flex; align-items: center; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.05)); padding: 1rem 2rem; border-radius: 16px; font-size: 0.875rem; color: #059669; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-shield-alt" style="margin-right: 0.75rem; font-size: 1rem;"></i>
                        <span style="font-weight: 600;">100% Free • Enterprise Security • No Spam Ever</span>
                    </div>
                </div>
            @endauth
        </div>
    </section>

    <!-- Executive Content Section -->
    <main style="padding-bottom: 2rem; position: relative; z-index: 1;">
        <div class="intel-container">
            
            <!-- Premium Prophecy Dates Section -->
            <section style="margin-bottom: 2rem;">
                <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.9)); backdrop-filter: blur(20px); border-radius: 17px; padding: 2.1rem; box-shadow: 0 8px 34px rgba(0, 0, 0, 0.08); border: 1px solid rgba(59, 130, 246, 0.1); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(135deg, #3b82f6, #1d4ed8);"></div>
                    
                    <div style="text-align: center; margin-bottom: 2.1rem;">
                        <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.05rem; box-shadow: 0 6px 22px rgba(59, 130, 246, 0.3);">
                            <i class="fas fa-calendar-check" style="color: white; font-size: 1.4rem;"></i>
                        </div>
                        <h3 style="font-size: 1.93rem; font-weight: 900; color: #0f172a; margin-bottom: 0.7rem; letter-spacing: -0.025em;">
                            Jebikalam Vaanga Telecast Dates
                        </h3>
                        <p style="font-size: 0.88rem; color: #475569; max-width: 700px; margin: 0 auto; line-height: 1.6; font-weight: 500;">
                            @auth
                                Prophecies shared in the Jebikalam Vaanga program are available month-wise for believers to pray over and receive spiritual guidance.
                            @else
                                Create your premium account to access prophecies from these blessed telecast dates and begin your spiritual journey.
                            @endauth
                        </p>
                    </div>

                    @if(count($groupedDates) > 0)
                        <!-- Month Selection Dropdown -->
                        <div style="margin-bottom: 2rem; text-align: center;">
                            <select id="monthSelector" class="month-selector" style="padding: 1rem 2rem; border: 2px solid var(--intel-gray-200); border-radius: var(--radius-md); font-size: 1rem; font-weight: 600; background: white; color: var(--intel-gray-700); min-width: 250px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);" onchange="showMonthDates(this.value)">
                                <option value="">Select a Month</option>
                                @foreach($groupedDates as $monthData)
                                    <option value="{{ $monthData['month_key'] }}">{{ $monthData['month_name'] }} ({{ $monthData['prophecy_count'] }} {{ $monthData['prophecy_count'] == 1 ? 'prophecy' : 'prophecies' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Premium Prophecy Grid (Initially Hidden) -->
                        <div id="prophecyDatesContainer" style="display: none;">
                            @foreach($groupedDates as $monthData)
                                <div id="month-{{ $monthData['month_key'] }}" class="month-dates" style="display: none;">
                                    <div style="margin-bottom: 1.5rem; text-align: center;">
                                        <h4 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $monthData['month_name'] }}</h4>
                                        <p style="font-size: 0.875rem; color: #64748b; margin: 0.5rem 0 0 0;">{{ $monthData['prophecy_count'] }} {{ $monthData['prophecy_count'] == 1 ? 'prophecy' : 'prophecies' }} available</p>
                                    </div>
                                    
                                    <div class="date-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1.4rem; max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
                                        @foreach($monthData['dates'] as $dateInfo)
                                            @auth
                                                <a href="{{ route('prophecies.show', ['id' => $dateInfo['prophecy_id'], 'language' => auth()->user()->preferred_language ?? 'en']) }}" 
                                                   class="date-card" style="display: block; width: 224px; flex-shrink: 0; background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); backdrop-filter: blur(20px); border-radius: 14px; padding: 1.4rem; text-align: center; box-shadow: 0 8px 32px rgba(124, 58, 237, 0.25); border: 1.4px solid rgba(147, 51, 234, 0.3); text-decoration: none; color: white; transition: all 0.3s ease; cursor: pointer;"
                                                   onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 12px 40px rgba(147, 51, 234, 0.4)'; this.style.borderColor='rgba(147, 51, 234, 0.6)'; this.style.background='linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)';"
                                                   onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 32px rgba(124, 58, 237, 0.25)'; this.style.borderColor='rgba(147, 51, 234, 0.3)'; this.style.background='linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%)';"
                                                   onmousedown="this.style.transform='translateY(-2px) scale(1.01)'; this.style.boxShadow='0 8px 30px rgba(147, 51, 234, 0.45)';"
                                                   onmouseup="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 12px 40px rgba(147, 51, 234, 0.4)';">
                                            @else
                                                <div class="date-card" style="width: 224px; flex-shrink: 0; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); border-radius: 14px; padding: 1.4rem; text-align: center; box-shadow: 0 8px 32px rgba(107, 114, 128, 0.25); opacity: 0.6; cursor: not-allowed; position: relative; overflow: hidden; border: 1.4px solid rgba(156, 163, 175, 0.3); color: white;">
                                            @endauth
                                                <!-- Premium Date Display -->
                                                <div style="margin-bottom: 0.74rem;">
                                                    <div style="font-size: 2.45rem; font-weight: 900; color: white; margin-bottom: 0.25rem; line-height: 1; letter-spacing: -0.025em;">
                                                        {{ \Carbon\Carbon::parse($dateInfo['jebikalam_vanga_date'])->format('d') }}
                                                    </div>
                                                    <div style="font-size: 0.7rem; font-weight: 700; color: rgba(255, 255, 255, 0.7); text-transform: uppercase; letter-spacing: 0.1em;">
                                                        {{ \Carbon\Carbon::parse($dateInfo['jebikalam_vanga_date'])->format('M Y') }}
                                                    </div>
                                                </div>

                                                <!-- Premium Prophecy Badge -->
                                                <div style="margin-bottom: 0.74rem;">
                                                    <div style="display: inline-flex; align-items: center; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 0.37rem 0.74rem; border-radius: 6px; font-size: 0.61rem; font-weight: 700; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); letter-spacing: 0.025em;">
                                                        <i class="fas fa-scroll" style="margin-right: 0.25rem;"></i>
                                                        PROPHECY
                                                    </div>
                                                </div>

                                                @guest
                                                    <div style="display: inline-flex; align-items: center; font-size: 0.37rem; color: rgba(255, 255, 255, 0.8); background: rgba(0, 0, 0, 0.2); padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600;">
                                                        <i class="fas fa-lock" style="margin-right: 0.25rem;"></i>
                                                        PREMIUM ACCESS REQUIRED
                                                    </div>
                                                @endguest
                                            @auth
                                                </a>
                                            @else
                                                </div>
                                            @endauth
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Premium Empty State -->
                        <div style="text-align: center; padding: 4rem 2rem;">
                            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, rgba(100, 116, 139, 0.1), rgba(148, 163, 184, 0.05)); border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; font-size: 3rem; color: #94a3b8;">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h4 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">No Prophecies Available</h4>
                            <p style="font-size: 1.25rem; color: #64748b; font-weight: 500;">New prophecies will be added soon. Check back for divine revelations!</p>
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </main>

    <!-- Executive Footer -->
    <footer style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); color: white; padding: 3rem 0; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></g></svg>');"></div>
        <div class="intel-container" style="text-align: center; position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-dove" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <span style="font-size: 2rem; font-weight: 900; letter-spacing: -0.025em;">Jebikalam Vaanga Prophecy</span>
            </div>
            <p style="font-size: 1.25rem; color: #94a3b8; margin-bottom: 2rem; font-weight: 500;">
                Taking Prophecies to Prophetic Prayer Warriors across the Globe
            </p>
            <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; font-size: 1rem; color: #cbd5e1; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <span style="display: flex; align-items: center; font-weight: 600;"><i class="fas fa-shield-alt" style="margin-right: 0.5rem; color: #10b981;"></i>Enterprise Security</span>
                <span style="display: flex; align-items: center; font-weight: 600;"><i class="fas fa-globe" style="margin-right: 0.5rem; color: #3b82f6;"></i>Multi-Language</span>
            </div>
            <div style="font-size: 0.875rem; color: #64748b; font-weight: 500;">
                Built with ❤️ for His Glory
            </div>
        </div>
    </footer>
</div>
@endsection

@push('styles')
<style>
/* Interactive Prophecy Cards */
.prophecy-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.prophecy-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
    border-color: rgba(59, 130, 246, 0.3);
}

.prophecy-card:active {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.2);
}

.prophecy-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
    transition: left 0.5s ease;
    z-index: 1;
}

.prophecy-card:hover::before {
    left: 100%;
}

.prophecy-card-content {
    position: relative;
    z-index: 2;
}

/* Language Indicator Hover Effects */
.language-indicator {
    transition: all 0.2s ease;
}

.language-indicator:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Prophecy Badge Hover Effect */
.prophecy-badge {
    transition: all 0.2s ease;
}

.prophecy-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
}

/* Click Ripple Effect */
@keyframes ripple {
    0% {
        transform: scale(0);
        opacity: 1;
    }
    100% {
        transform: scale(4);
        opacity: 0;
    }
}

.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

/* Executive Responsive Design */
@media (max-width: 768px) {
    .intel-container > div[style*="display: flex"] {
        flex-direction: column !important;
        gap: 1rem !important;
        text-align: center !important;
    }
    
    h2[style*="font-size: 2.45rem"] {
        font-size: 1.75rem !important;
    }
    
    h2[style*="font-size: 2.8rem"] {
        font-size: 2.1rem !important;
    }
    
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
    
    footer div[style*="display: flex"] {
        flex-direction: column !important;
        gap: 1rem !important;
    }
}


/* Executive Typography */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Premium Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
}

/* Responsive Date Cards */
@media (max-width: 768px) {
    .date-card {
        width: 180px !important;
    }
    
    .date-container {
        padding: 0 0.5rem !important;
        gap: 1rem !important;
    }
    
    .month-selector {
        min-width: 200px !important;
        font-size: 0.9rem !important;
    }
}

@media (max-width: 480px) {
    .date-card {
        width: 160px !important;
    }
    
    .date-container {
        gap: 0.8rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Month selection functionality
function showMonthDates(monthKey) {
    const container = document.getElementById('prophecyDatesContainer');
    const allMonths = document.querySelectorAll('.month-dates');
    
    // Hide all month sections
    allMonths.forEach(month => {
        month.style.display = 'none';
    });
    
    if (monthKey) {
        // Show selected month
        const selectedMonth = document.getElementById('month-' + monthKey);
        if (selectedMonth) {
            container.style.display = 'block';
            selectedMonth.style.display = 'block';
            
            // Smooth scroll to the dates
            setTimeout(() => {
                selectedMonth.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }, 100);
        }
    } else {
        // Hide container if no month selected
        container.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to prophecy cards
    function createRipple(event) {
        const card = event.currentTarget;
        const rect = card.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        
        card.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // Add ripple effect to all prophecy cards
    document.querySelectorAll('a[href*="prophecies"]').forEach(card => {
        card.addEventListener('click', createRipple);
        
        // Add loading state
        card.addEventListener('click', function(e) {
            this.style.opacity = '0.8';
            this.style.pointerEvents = 'none';
            
            // Create loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin" style="color: #3b82f6; font-size: 1.2rem;"></i>';
            loadingDiv.style.cssText = `
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 10;
                background: rgba(255, 255, 255, 0.9);
                padding: 0.5rem;
                border-radius: 50%;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            `;
            this.appendChild(loadingDiv);
        });
    });
    
    // Enhanced hover effects for language indicators
    document.querySelectorAll('[title]').forEach(element => {
        if (element.textContent.match(/^[A-Z]{2}$/)) { // Language codes
            element.classList.add('language-indicator');
        }
    });
    
    // Enhanced hover effects for prophecy badges
    document.querySelectorAll('[style*="PROPHECY"]').forEach(element => {
        element.classList.add('prophecy-badge');
    });
    
    // Add subtle pulse animation to cards on page load
    const cards = document.querySelectorAll('a[href*="prophecies"]');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            const focusedElement = document.activeElement;
            if (focusedElement.href && focusedElement.href.includes('prophecies')) {
                e.preventDefault();
                focusedElement.click();
            }
        }
    });
    
    // Add focus styles for accessibility
    document.querySelectorAll('a[href*="prophecies"]').forEach(card => {
        card.addEventListener('focus', function() {
            this.style.outline = '2px solid #3b82f6';
            this.style.outlineOffset = '2px';
        });
        
        card.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });
});
</script>
@endpush
