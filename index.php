<?php
require_once 'header.php';
?>

<main>
    <section id="home" class="hero-section">
        <div class="container hero-container">
            <div class="hero-content reveal-slide-right">
                <span class="badge">Eco-Friendly & Intelligent</span>
                <h1 class="hero-title">Solid Waste Collection Scheduling</h1>
                <p class="hero-subtitle">
                    Streamlining city waste pickup and recycling for healthier communities. Schedule pickups on demand, monitor real-time routes, and keep your neighborhood green.
                </p>
                <div class="hero-actions">
                    <a href="#cta" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="#schedule" class="btn btn-outline btn-lg">View Schedule</a>
                </div>
            </div>
            <div class="hero-image-wrapper reveal-slide-left">
                <div class="hero-card-glass">
                    <div class="svg-hero-illustration">
                        <svg viewBox="0 0 500 400" width="100%" height="100%">
                            <defs>
                                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#2E7D32;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#1976D2;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <circle cx="250" cy="200" r="160" fill="url(#grad1)" opacity="0.15" />
                            <rect x="120" y="140" width="260" height="180" rx="16" fill="#FFFFFF" shadow="true" />
                            <rect x="140" y="160" width="220" height="20" rx="6" fill="#E8F5E9" />
                            <rect x="140" y="195" width="160" height="14" rx="4" fill="#E0E0E0" />
                            <rect x="140" y="220" width="180" height="14" rx="4" fill="#E0E0E0" />
                            <circle cx="330" cy="265" r="30" fill="#2E7D32" />
                            <path d="M320 265 l8 8 l16 -16" stroke="#FFFFFF" stroke-width="4" fill="none" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about-section section-padding">
        <div class="container">
            <div class="section-header text-center reveal-fade">
                <h2>Organized Collection For Better Living</h2>
                <p>We combine technology and operational logistics to minimize environmental impact and maximize recycling rates.</p>
            </div>

            <div class="about-grid">
                <div class="about-card reveal-fade">
                    <div class="about-icon">
                        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <h3>For Residents</h3>
                    <p>Never miss a pickup day again. Track trucks live in your neighborhood, log bulky item requests, and view recycling guidelines.</p>
                </div>

                <div class="about-card reveal-fade">
                    <div class="about-icon">
                        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13"/>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                            <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                    </div>
                    <h3>For Waste Collectors</h3>
                    <p>Dynamic route optimization reduces fuel consumption and minimizes delays, improving overall team efficiency and safety.</p>
                </div>

                <div class="about-card reveal-fade">
                    <div class="about-icon">
                        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3>Environmental Impact</h3>
                    <p>Structured scheduled collection prevents landfill overflow, mitigates illegal dumping, and encourages active urban sorting.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. FEATURES SECTION -->
    <section id="services" class="features-section section-padding bg-light">
        <div class="container">
            <div class="section-header text-center reveal-fade">
                <h2>Smart Tools For Smarter Cities</h2>
            </div>

            <div class="features-grid">
                <div class="feature-card reveal-fade">
                    <div class="feature-icon">📅</div>
                    <h3>Online Scheduling</h3>
                    <p>Book special bulk waste or hazardous material pickups directly from your smartphone or desktop.</p>
                </div>

                <div class="feature-card reveal-fade">
                    <div class="feature-icon">🗺️</div>
                    <h3>Collection Calendar</h3>
                    <p>Interactive calendar categorized by organic, recyclable, and general household waste types.</p>
                </div>

                <!-- UPDATED FEATURE CARD HERE -->
                <div class="feature-card reveal-fade">
                    <div class="feature-icon">📍</div>
                    <h3>Real-Time Truck Tracking</h3>
                    <p>Monitor live GPS locations of collection trucks in your zone so you know exactly when to place your bins out.</p>
                </div>

                <div class="feature-card reveal-fade">
                    <div class="feature-icon">🚚</div>
                    <h3>Route Management</h3>
                    <p>Optimized driver navigation pathways ensure timely servicing and reduced carbon emissions across municipal sectors.</p>
                </div>

                <div class="feature-card reveal-fade">
                    <div class="feature-icon">📊</div>
                    <h3>Waste Reports</h3>
                    <p>Access municipal data and personal household metrics to track monthly recycling efficiency over time.</p>
                </div>

                <div class="feature-card reveal-fade">
                    <div class="feature-icon">⚙️</div>
                    <h3>Admin Dashboard</h3>
                    <p>Centralized dispatch system for municipal managers to assign routes and handle citizen requests.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. HOW IT WORKS SECTION -->
    <section class="how-it-works section-padding">
        <div class="container">
            <div class="section-header text-center reveal-fade">
                <h2>How It Works</h2>
            </div>

            <div class="steps-wrapper">
                <div class="step-card reveal-fade">
                    <div class="step-number">1</div>
                    <div class="step-icon">👤</div>
                    <h3>Register Account</h3>
                    <p>Sign up with your residential address and contact information to assign your zone.</p>
                </div>

                <div class="step-card reveal-fade">
                    <div class="step-number">2</div>
                    <div class="step-icon">📆</div>
                    <h3>Schedule Pickup</h3>
                    <p>Select your preferred dates or view fixed recurring collection windows.</p>
                </div>

                <div class="step-card reveal-fade">
                    <div class="step-number">3</div>
                    <div class="step-icon">📍</div>
                    <h3>Track Truck Live</h3>
                    <p>Follow the collection truck’s arrival progress on an interactive live map in real time.</p>
                </div>

                <div class="step-card reveal-fade">
                    <div class="step-number">4</div>
                    <div class="step-icon">♻️</div>
                    <h3>Collection Done</h3>
                    <p>Place bins out as the truck approaches. Our automated crew handles prompt, clean collection.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. STATISTICS SECTION (INITIAL LAUNCH STATE) -->
    <section class="stats-section section-padding">
        <div class="container text-center">
            <h2 style="color: #ffffff; margin-bottom: 0.75rem;">Building a Cleaner Iligan City Together</h2>
            <p style="color: rgba(255, 255, 255, 0.85); max-width: 650px; margin: 0 auto;">
                Our digital waste scheduling system is officially live! As residents, barangay officials, and collection crews register across Iligan City, real-time statistics and analytics will populate here.
            </p>
        </div>
    </section>

    <!-- 6. COLLECTION SCHEDULE PREVIEW (INITIAL LAUNCH STATE) -->
    <section id="schedule" class="schedule-section section-padding bg-light">
        <div class="container">
            <div class="section-header text-center reveal-fade">
                <h2>Weekly Collection Schedule Preview</h2>
                <p>Detailed barangay-level route schedules will appear here once initial zone registrations are finalized.</p>
            </div>

            <div class="empty-state-card reveal-fade">
                <div class="empty-state-icon">📅</div>
                <h3>No Active Schedules Posted Yet</h3>
                <p>Collection routes for Iligan City barangays are currently being mapped and configured. Check back soon or create an account to receive schedule announcements for your specific zone.</p>
                <a href="#" class="btn btn-outline btn-sm js-open-login" data-tab="register" style="margin-top: 1rem;">Register Your Barangay Household</a>
            </div>
        </div>
    </section>

    <!-- 7. TESTIMONIALS SECTION (INITIAL LAUNCH STATE) -->
    <section class="testimonials-section section-padding">
        <div class="container">
            <div class="section-header text-center reveal-fade">
                <h2>What Our Residents Say</h2>
                <p>Community feedback and reviews will be featured here as Iligan residents use the system.</p>
            </div>

            <div class="empty-state-card reveal-fade">
                <div class="empty-state-icon">💬</div>
                <h3>Be One of Our First Reviewers</h3>
                <p>We are just getting started! Once collection services begin in your area, you'll be able to rate your service and share feedback to help us keep Iligan City clean and sustainable.</p>
            </div>
        </div>
    </section>

    <!-- 8. FREQUENTLY ASKED QUESTIONS -->
    <section id="faq" class="faq-section section-padding bg-light">
        <div class="container">
            <div class="section-header text-center reveal-fade">
                <h2>Frequently Asked Questions</h2>
            </div>

            <div class="accordion-container reveal-fade">
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>How does real-time truck tracking work?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>Once logged in, open the "Live Map" tab in your dashboard. You can view the live GPS signal of the assigned vehicle as it navigates through your collection zone.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>How do I schedule a special pickup for bulky items?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>Log in to your account, click on "Request Pickup", choose "Bulky Item", select an available day, and specify the items (e.g., furniture, appliances).</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>What happens if my scheduled pickup is missed?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>If your bin was placed out on time and was missed, you can submit a "Missed Collection" report directly on your dashboard for same-day dispatch re-routing.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Are electronic wastes (e-waste) accepted in regular bins?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>No, electronic devices contain hazardous materials and must be scheduled under the "E-Waste Disposal" category or taken to a designated drop-off depot.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. CALL TO ACTION SECTION -->
    <section id="cta" class="cta-section section-padding">
        <div class="container text-center reveal-fade">
            <h2 class="cta-title">Ready for a Cleaner Environment?</h2>
            <p class="cta-subtitle">Join thousands of residents actively transforming municipal waste management today.</p>
            <div class="cta-buttons">
                <a href="#" class="btn btn-secondary btn-lg js-open-login" data-tab="register">Create an Account</a>
                <a href="#contact" class="btn btn-outline-white btn-lg">Contact Support</a>
            </div>
        </div>
    </section>

</main>

<?php
require_once 'footer.php';
?>