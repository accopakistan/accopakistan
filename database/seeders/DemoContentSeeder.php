<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Client;
use App\Models\Faq;
use App\Models\JobPosting;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DemoContentSeeder extends Seeder
{
    protected function image(string $seed, int $w = 1600, int $h = 1000): string
    {
        return "https://picsum.photos/seed/{$seed}/{$w}/{$h}";
    }

    protected function attachImage(mixed $model, string $collection, string $seed, int $w = 1600, int $h = 1000): void
    {
        try {
            if ($model->getFirstMedia($collection)) {
                return;
            }
            $model->addMediaFromUrl($this->image($seed, $w, $h))->toMediaCollection($collection);
        } catch (\Throwable $e) {
            Log::warning("DemoContentSeeder: could not fetch image for {$collection}/{$seed}: {$e->getMessage()}");
        }
    }

    protected function settingImage(string $key, string $group, string $seed, int $w = 1920, int $h = 1080): void
    {
        if (Setting::get($key)) {
            return;
        }

        try {
            $contents = Http::timeout(20)->get($this->image($seed, $w, $h))->body();
            $path = 'settings/'.$seed.'.jpg';
            Storage::disk('public')->put($path, $contents);
            Setting::set($key, $path, $group, 'image');
        } catch (\Throwable $e) {
            Log::warning("DemoContentSeeder: could not fetch setting image {$key}: {$e->getMessage()}");
        }
    }

    public function run(): void
    {
        $author = User::first();

        // ---------------------------------------------------------------
        // Project categories
        // ---------------------------------------------------------------
        $categories = [];
        foreach (['Commercial', 'Healthcare', 'Industrial', 'Residential', 'Institutional'] as $i => $name) {
            $categories[$name] = ProjectCategory::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name, 'order' => $i]
            );
        }

        // ---------------------------------------------------------------
        // Blog categories
        // ---------------------------------------------------------------
        $blogCategories = [];
        foreach (['Company News', 'Industry Insights', 'Design Trends', 'Sustainability'] as $i => $name) {
            $blogCategories[$name] = BlogCategory::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name, 'order' => $i]
            );
        }

        // ---------------------------------------------------------------
        // Services
        // ---------------------------------------------------------------
        $services = [
            [
                'title' => 'Architectural Design',
                'icon' => 'architecture',
                'hero_tagline' => 'Where vision becomes drawings, and drawings become buildings.',
                'excerpt' => 'End-to-end architectural design, from first concept sketch to construction-ready documentation, for commercial, institutional, and residential projects across Pakistan.',
                'content' => "Every project we design starts with a brief, not a template. Our architectural team spends real time understanding how a building will actually be used before a single line is drawn — how people will move through it, how light will fall across a floor plate, how it will read from the street.\n\nFrom there we move through concept design, schematic design, and detailed design in close collaboration with our in-house structural and MEP engineers, so the drawings that reach a construction site have already been tested against buildability and cost.\n\nWe have designed office towers, hospitals, manufacturing plants, and private residences across Rawalpindi, Lahore, Karachi, and Islamabad, and we carry the same rigor into every scale of project.",
                'benefits' => [
                    ['title' => 'One design language, fully coordinated', 'description' => 'Architecture, structure, and MEP are developed together internally, so drawings are coordinated before they ever reach site.'],
                    ['title' => 'Climate-responsive by default', 'description' => 'Every design accounts for Pakistan\'s heat, dust, and seasonal monsoon loads from the first concept sketch.'],
                    ['title' => 'Code compliance built in', 'description' => 'Our documentation is developed against local building codes and civic approval requirements from day one, reducing approval delays.'],
                    ['title' => 'Design that respects budget', 'description' => 'Our architects work against a live cost model throughout design, so the final tender package is one you can actually build.'],
                ],
                'process_steps' => [
                    ['title' => 'Brief & Site Analysis', 'description' => 'We study the site, the client brief, and the regulatory context before any design work begins.'],
                    ['title' => 'Concept Design', 'description' => 'Initial massing, spatial planning, and design direction are presented for client review and feedback.'],
                    ['title' => 'Schematic Design', 'description' => 'Floor plans, elevations, and material direction are developed and coordinated with engineering.'],
                    ['title' => 'Detailed Design', 'description' => 'Construction-ready drawings, specifications, and tender documentation are issued.'],
                ],
            ],
            [
                'title' => 'Structural & MEP Engineering',
                'icon' => 'engineering',
                'hero_tagline' => 'The engineering that keeps a building standing, breathing, and running.',
                'excerpt' => 'In-house structural, mechanical, electrical, and plumbing engineering integrated with architectural design from day one, not bolted on afterward.',
                'content' => "Structural and MEP engineering decide whether a beautiful design is actually buildable, affordable, and safe. Our in-house engineering team works alongside our architects from the concept stage, rather than reviewing finished drawings after the fact.\n\nOur structural engineers design for Pakistan's seismic zones using reinforced concrete, structural steel, and composite systems suited to each project's loads and budget. Our MEP engineers design HVAC, electrical distribution, fire protection, and plumbing systems sized correctly the first time, avoiding the retrofit costs that come from underspecified building services.\n\nBecause the same firm designs the architecture and the engineering, clashes between disciplines get caught in the design studio, not on the construction site.",
                'benefits' => [
                    ['title' => 'Seismic-appropriate structural design', 'description' => 'All structural systems are designed against Pakistan\'s seismic zoning requirements, not generic international defaults.'],
                    ['title' => 'Right-sized MEP systems', 'description' => 'HVAC, electrical, and plumbing loads are calculated against real occupancy and climate data, not rules of thumb.'],
                    ['title' => 'Fewer site clashes', 'description' => 'Structural and MEP models are coordinated with architecture before construction, reducing costly on-site rework.'],
                    ['title' => 'Full calculation packages', 'description' => 'Every structural and MEP submission includes complete calculations ready for regulatory approval.'],
                ],
                'process_steps' => [
                    ['title' => 'Load & Systems Analysis', 'description' => 'Structural loads and MEP demand are calculated against the architectural brief.'],
                    ['title' => 'Concept Engineering', 'description' => 'Structural systems and MEP strategy are proposed and tested against the design.'],
                    ['title' => 'Detailed Engineering', 'description' => 'Full structural drawings, MEP layouts, and calculation packages are produced.'],
                    ['title' => 'Site Support', 'description' => 'Engineers remain available through construction to resolve site queries quickly.'],
                ],
            ],
            [
                'title' => 'Construction Management',
                'icon' => 'construction',
                'hero_tagline' => 'One team accountable from groundbreaking to handover.',
                'excerpt' => 'End-to-end construction management ensuring quality, safety, cost control, and on-time delivery, backed by a single point of accountability.',
                'content' => "Construction is where design either holds up or falls apart. Our construction management team plans the sequencing, procurement, and site supervision needed to deliver what was actually designed, on the schedule the client was promised.\n\nWe run a disciplined site management model: certified safety officers on every active site, weekly progress reporting against a live schedule, and a procurement team that sources materials against specification rather than convenience.\n\nBecause our construction managers work from the same drawings our architects and engineers produced, there is no handoff gap between design intent and site execution — the people managing the build understand why every detail was drawn the way it was.",
                'benefits' => [
                    ['title' => 'Single point of accountability', 'description' => 'One project manager owns schedule, budget, and quality from mobilization to handover.'],
                    ['title' => 'Certified site safety', 'description' => 'Every active site operates under a documented safety plan with certified safety officers present.'],
                    ['title' => 'Transparent progress reporting', 'description' => 'Clients receive weekly progress reports against a live schedule, not end-of-month surprises.'],
                    ['title' => 'Disciplined procurement', 'description' => 'Materials are procured against specification and lead time, protecting both quality and schedule.'],
                ],
                'process_steps' => [
                    ['title' => 'Mobilization', 'description' => 'Site setup, procurement planning, and subcontractor onboarding.'],
                    ['title' => 'Construction', 'description' => 'Phased construction under continuous quality and safety supervision.'],
                    ['title' => 'Testing & Commissioning', 'description' => 'All building systems are tested and commissioned before handover.'],
                    ['title' => 'Handover', 'description' => 'Final inspection, snag resolution, and documentation handover to the client.'],
                ],
            ],
            [
                'title' => 'Interior Design',
                'icon' => 'interior',
                'hero_tagline' => 'The spaces people actually experience, designed with intent.',
                'excerpt' => 'Interior design for commercial, healthcare, and hospitality spaces that balances material honesty, comfort, and long-term durability.',
                'content' => "The interior is where a building is actually experienced day to day. Our interior design team works on offices, retail environments, clinical spaces, and residences, developing material palettes, lighting design, and furniture layouts that hold up to real use, not just first impressions.\n\nFor commercial and hospitality clients, we design spaces that support how people work and move. For healthcare clients, we design interiors around infection control, wayfinding, and patient comfort. In every case, we specify materials that are locally sourceable and durable under Pakistan's climate and maintenance conditions.\n\nOur interior packages are developed alongside our architecture and MEP teams, so lighting, ceiling services, and finishes are coordinated rather than an afterthought.",
                'benefits' => [
                    ['title' => 'Function-first material selection', 'description' => 'Materials are chosen for durability and maintainability under real-world use, not just visual appeal.'],
                    ['title' => 'Coordinated with MEP', 'description' => 'Lighting, ceiling services, and finishes are developed alongside mechanical and electrical design.'],
                    ['title' => 'Locally sourceable specifications', 'description' => 'We specify materials that are actually available in Pakistan\'s supply chain, avoiding import delays.'],
                    ['title' => 'Built for maintenance', 'description' => 'Interior finishes are chosen with facility management and long-term upkeep in mind.'],
                ],
                'process_steps' => [
                    ['title' => 'Space Planning', 'description' => 'Layout and functional planning based on how the space will actually be used.'],
                    ['title' => 'Material & Lighting Design', 'description' => 'Palette, finishes, and lighting design are developed and presented for approval.'],
                    ['title' => 'Documentation', 'description' => 'Detailed interior drawings and specifications are issued for procurement and fit-out.'],
                    ['title' => 'Fit-Out Supervision', 'description' => 'Our team supervises fit-out contractors to protect design intent through installation.'],
                ],
            ],
            [
                'title' => 'Project Management',
                'icon' => 'management',
                'hero_tagline' => 'Keeping every discipline moving on the same schedule.',
                'excerpt' => 'Independent project management services coordinating architecture, engineering, procurement, and construction against a single master schedule and budget.',
                'content' => "Large projects fail more often from poor coordination than poor design. Our project management service gives clients a single team responsible for aligning architects, engineers, contractors, and suppliers against one master schedule and budget.\n\nWe act as the client's representative on site, managing change orders, resolving design queries, and holding every party accountable to the program. For clients who already have their own architect or contractor engaged, we can be brought in specifically to manage that coordination independently.\n\nOur project managers report on cost, schedule, and risk in a format built for decision-making, not just documentation — so clients always know where a project actually stands.",
                'benefits' => [
                    ['title' => 'Independent oversight', 'description' => 'We can manage projects designed or built by other firms, giving clients an independent check on progress and cost.'],
                    ['title' => 'Single master schedule', 'description' => 'Every discipline is coordinated against one program, reducing the delays caused by siloed planning.'],
                    ['title' => 'Proactive risk management', 'description' => 'Risks are flagged and mitigated before they become schedule or cost overruns.'],
                    ['title' => 'Clear cost reporting', 'description' => 'Clients receive cost reports built for decision-making, not just record-keeping.'],
                ],
                'process_steps' => [
                    ['title' => 'Program Setup', 'description' => 'A master schedule and budget baseline are established across all disciplines.'],
                    ['title' => 'Coordination', 'description' => 'Weekly coordination between architects, engineers, and contractors against the program.'],
                    ['title' => 'Reporting', 'description' => 'Regular cost, schedule, and risk reporting to the client.'],
                    ['title' => 'Close-Out', 'description' => 'Final account reconciliation and documentation handover.'],
                ],
            ],
            [
                'title' => 'Healthcare Facility Design',
                'icon' => 'healthcare',
                'hero_tagline' => 'Hospitals designed around clinical workflow, not just floor area.',
                'excerpt' => 'Hospitals and clinics engineered around clinical workflow, infection control, patient experience, and the operational realities of running a healthcare facility in Pakistan.',
                'content' => "Healthcare design is unlike any other building type: every corridor width, every door swing, and every ventilation zone is shaped by clinical workflow and infection control requirements. Our healthcare design practice works closely with medical planners and hospital administrators to design facilities that function under real operational pressure, not just on paper.\n\nWe design patient flow to separate clean and contaminated pathways, size mechanical systems for negative and positive pressure rooms where required, and plan for the equipment loads that modern diagnostic and surgical departments actually need.\n\nOur healthcare projects include general hospitals, specialty clinics, and diagnostic centers, each designed in direct consultation with the clinical teams who will run them.",
                'benefits' => [
                    ['title' => 'Clinical workflow-led planning', 'description' => 'Layouts are designed around actual patient and staff flow, developed with input from clinical teams.'],
                    ['title' => 'Infection control by design', 'description' => 'Circulation, ventilation, and finishes are planned to support infection prevention protocols.'],
                    ['title' => 'Equipment-ready engineering', 'description' => 'MEP systems are sized for the real electrical, medical gas, and cooling loads of modern clinical equipment.'],
                    ['title' => 'Regulatory-ready documentation', 'description' => 'Design documentation is developed to support healthcare facility licensing and approvals.'],
                ],
                'process_steps' => [
                    ['title' => 'Clinical Briefing', 'description' => 'Workflow, department adjacencies, and equipment requirements are established with clinical stakeholders.'],
                    ['title' => 'Facility Planning', 'description' => 'Departmental layouts and patient flow are designed and tested against operational scenarios.'],
                    ['title' => 'Engineering Integration', 'description' => 'Specialist MEP systems for medical gas, isolation rooms, and imaging are engineered in detail.'],
                    ['title' => 'Commissioning Support', 'description' => 'Our team supports clinical and technical commissioning ahead of facility opening.'],
                ],
            ],
            [
                'title' => 'Industrial & Manufacturing Facilities',
                'icon' => 'industrial',
                'hero_tagline' => 'Facilities built around throughput, safety, and growth.',
                'excerpt' => 'Manufacturing and logistics facilities engineered for production throughput, safety compliance, and rapid future expansion.',
                'content' => "Industrial facilities succeed or fail on operational logic: how raw material enters, how it moves through production, and how finished goods leave. Our industrial design practice starts with process flow, then builds the structure, services, and safety systems around it.\n\nWe design factory floors, warehouses, and logistics facilities with the structural clear spans, floor loading, and utility capacity that manufacturing equipment actually demands, and we build in the fire safety and life safety systems required for industrial occupancy.\n\nWe also design for growth — phasing structures and utility infrastructure so that clients can expand production without demolishing what came before.",
                'benefits' => [
                    ['title' => 'Process-led facility design', 'description' => 'Building layout follows actual production and logistics flow, minimizing material handling costs.'],
                    ['title' => 'Engineered for real loads', 'description' => 'Structural clear spans and floor loading are engineered against actual equipment and storage requirements.'],
                    ['title' => 'Built-in safety compliance', 'description' => 'Fire safety, ventilation, and life safety systems are designed to meet industrial occupancy codes.'],
                    ['title' => 'Designed to expand', 'description' => 'Structural and utility systems are planned with future phases in mind, avoiding costly retrofits.'],
                ],
                'process_steps' => [
                    ['title' => 'Process Mapping', 'description' => 'Production and logistics flow are mapped before layout begins.'],
                    ['title' => 'Facility Design', 'description' => 'Structure, utilities, and safety systems are designed around the process.'],
                    ['title' => 'Construction', 'description' => 'Fast-track construction methods are used to minimize time to production.'],
                    ['title' => 'Commissioning', 'description' => 'Utilities and safety systems are tested and commissioned ahead of operations start-up.'],
                ],
            ],
        ];

        foreach ($services as $i => $data) {
            $service = Service::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['title'])],
                [
                    'title' => $data['title'],
                    'icon' => $data['icon'],
                    'hero_tagline' => $data['hero_tagline'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'benefits' => $data['benefits'],
                    'process_steps' => $data['process_steps'],
                    'status' => 'published',
                    'is_featured' => $i < 3,
                    'order' => $i,
                ]
            );

            $this->attachImage($service, 'featured_image', 'acco-svc-'.$service->slug, 1600, 1000);
            $this->attachImage($service, 'gallery', 'acco-svc-'.$service->slug.'-g1', 1200, 900);
        }

        // ---------------------------------------------------------------
        // Projects
        // ---------------------------------------------------------------
        $projects = [
            [
                'title' => 'Blue Horizon Business Tower',
                'category' => 'Commercial',
                'client' => 'Zenith Business Park',
                'location' => 'Lahore, Pakistan',
                'completion_date' => '2024-03-15',
                'project_value' => 'PKR 2.1 Billion',
                'area' => '285,000 sq ft',
                'excerpt' => 'A 14-storey Grade-A office tower on Lahore\'s main commercial corridor, designed around column-free floor plates and a fully glazed curtain wall.',
                'scope' => 'Full architectural design, structural and MEP engineering, and construction management for a 14-storey commercial office tower including a 3-level basement parking structure.',
                'features' => 'Column-free floor plates for flexible tenant fit-out, energy-efficient glazed curtain wall system, dedicated backup power and water storage for uninterrupted operations, and a rooftop amenity floor.',
                'content' => "Blue Horizon Business Tower sits on Lahore's main commercial corridor and was designed to compete with the best Grade-A office space in the city. The brief called for column-free floor plates that could be reconfigured for any tenant, and a facade that would perform under Lahore's summer heat load without relying entirely on mechanical cooling.\n\nOur structural team developed a post-tensioned flat slab system that eliminated internal columns across each 20,000 square foot floor plate, while our facade engineering minimized solar heat gain through a double-glazed curtain wall with a low-E coating.\n\nThe tower was delivered on a 22-month construction program, with our construction management team coordinating over 40 subcontractors across structure, facade, and MEP fit-out.",
                'milestones' => [
                    ['title' => 'Design & Approvals', 'date' => 'Jan 2022 – Aug 2022'],
                    ['title' => 'Foundation & Structure', 'date' => 'Sep 2022 – Jun 2023'],
                    ['title' => 'Facade & MEP Fit-Out', 'date' => 'Jul 2023 – Jan 2024'],
                    ['title' => 'Handover', 'date' => 'Mar 2024'],
                ],
                'services_involved' => ['Architectural Design', 'Structural & MEP Engineering', 'Construction Management'],
                'is_featured' => true,
            ],
            [
                'title' => 'Al-Shifa Hospital Expansion',
                'category' => 'Healthcare',
                'client' => 'Al-Shifa Healthcare Trust',
                'location' => 'Rawalpindi, Pakistan',
                'completion_date' => '2023-11-20',
                'project_value' => 'PKR 1.4 Billion',
                'area' => '110,000 sq ft',
                'excerpt' => 'A 120-bed expansion to an existing hospital campus, adding a new surgical wing, diagnostic imaging department, and dedicated isolation ward.',
                'scope' => 'Healthcare facility design, MEP engineering for medical gas and isolation ventilation systems, and construction management delivered in phases to keep the existing hospital fully operational.',
                'features' => 'Four new operating theatres with dedicated sterile corridors, negative-pressure isolation ward, expanded diagnostic imaging suite, and a redesigned patient drop-off and emergency access route.',
                'content' => "This expansion added 120 beds and a full surgical wing to an operating hospital campus — without disrupting patient care during construction. Our healthcare design team worked directly with the hospital's clinical leadership to plan a phased build sequence that kept existing wards and the emergency department fully operational throughout.\n\nThe new surgical wing includes four operating theatres with separated clean and sterile corridors, and the isolation ward was engineered with dedicated negative-pressure ventilation independent from the rest of the hospital's air handling.\n\nCoordinating construction logistics around a live hospital required daily coordination meetings with hospital operations staff, noise and vibration monitoring near active wards, and strict infection control protocols on every access route through the site.",
                'milestones' => [
                    ['title' => 'Clinical Planning & Design', 'date' => 'Feb 2022 – Jul 2022'],
                    ['title' => 'Phase 1: Surgical Wing', 'date' => 'Aug 2022 – Apr 2023'],
                    ['title' => 'Phase 2: Isolation Ward & Imaging', 'date' => 'May 2023 – Sep 2023'],
                    ['title' => 'Commissioning & Handover', 'date' => 'Nov 2023'],
                ],
                'services_involved' => ['Healthcare Facility Design', 'Structural & MEP Engineering', 'Construction Management'],
                'is_featured' => true,
            ],
            [
                'title' => 'Meridian Business Park',
                'category' => 'Commercial',
                'client' => 'Continental Retail Group',
                'location' => 'Islamabad, Pakistan',
                'completion_date' => '2025-02-10',
                'project_value' => 'PKR 3.4 Billion',
                'area' => '410,000 sq ft',
                'excerpt' => 'A mixed-use business park combining Grade-A office space, ground-floor retail, and structured parking across three connected blocks.',
                'scope' => 'Master planning, architectural design, structural and MEP engineering, and full design-build delivery across three interconnected office and retail blocks.',
                'features' => 'Three interconnected blocks around a shared landscaped plaza, ground-floor retail frontage, structured parking for over 500 vehicles, and a shared central utility plant serving all three blocks.',
                'content' => "Meridian Business Park brought together office, retail, and structured parking into a single masterplanned campus on the outskirts of Islamabad. As design-build lead, ACCO was responsible for everything from initial site masterplanning through final handover.\n\nThe three office and retail blocks share a central landscaped plaza and a single utility plant, reducing long-term operating costs for tenants compared to standalone buildings. Ground-floor retail units were designed with double-height frontage to attract anchor tenants, while the upper office floors use the same column-free structural approach we developed for Blue Horizon Tower.\n\nAs design-build delivery partner, we carried single-point accountability for the full 30-month program, coordinating design, procurement, and construction under one contract.",
                'milestones' => [
                    ['title' => 'Masterplanning & Design', 'date' => 'Mar 2022 – Dec 2022'],
                    ['title' => 'Block A & B Construction', 'date' => 'Jan 2023 – Nov 2023'],
                    ['title' => 'Block C Construction', 'date' => 'Dec 2023 – Aug 2024'],
                    ['title' => 'Retail Fit-Out & Handover', 'date' => 'Feb 2025'],
                ],
                'services_involved' => ['Architectural Design', 'Structural & MEP Engineering', 'Construction Management', 'Project Management'],
                'is_featured' => true,
            ],
            [
                'title' => 'Northline Textile Manufacturing Plant',
                'category' => 'Industrial',
                'client' => 'Faisalabad Textile Mills',
                'location' => 'Faisalabad, Pakistan',
                'completion_date' => '2024-06-30',
                'project_value' => 'PKR 980 Million',
                'area' => '220,000 sq ft',
                'excerpt' => 'A single-storey textile manufacturing facility engineered for high-density weaving floor loads and phased future expansion.',
                'scope' => 'Industrial facility design, structural engineering for heavy production floor loads, and fast-track construction management.',
                'features' => 'Column grid engineered around weaving machine layouts, elevated utility gantry for services distribution, dedicated fire suppression system, and a structural frame designed for a future second phase without demolition.',
                'content' => "This manufacturing plant was designed around the client's weaving machine layout rather than a generic industrial shed template. Our structural team engineered the column grid specifically to avoid interrupting machine rows, and floor loading was calculated against the actual dead and live loads of the installed equipment.\n\nServices were routed through an elevated utility gantry rather than underground trenching, both to speed up construction and to make future maintenance and reconfiguration easier for the client's facilities team.\n\nThe entire structural frame was designed to support a planned Phase 2 expansion, so the client can double production capacity in the future without demolishing or retrofitting the existing structure.",
                'milestones' => [
                    ['title' => 'Process Mapping & Design', 'date' => 'Sep 2023 – Dec 2023'],
                    ['title' => 'Foundation & Steel Frame', 'date' => 'Jan 2024 – Apr 2024'],
                    ['title' => 'Utilities & Commissioning', 'date' => 'May 2024 – Jun 2024'],
                ],
                'services_involved' => ['Industrial & Manufacturing Facilities', 'Structural & MEP Engineering'],
                'is_featured' => false,
            ],
            [
                'title' => 'Willowgate Residences',
                'category' => 'Residential',
                'client' => 'Willowgate Properties',
                'location' => 'Lahore, Pakistan',
                'completion_date' => '2023-08-05',
                'project_value' => 'PKR 640 Million',
                'area' => '95,000 sq ft',
                'excerpt' => 'A gated residential development of 24 private villas designed around shared green space and passive cooling strategies.',
                'scope' => 'Architectural design and interior design for a 24-villa residential development, including shared landscape and community amenity design.',
                'features' => 'Passive cooling through cross-ventilated floor plans and deep roof overhangs, shared central green corridor connecting all villas, private rooftop terraces, and a dedicated community clubhouse.',
                'content' => "Willowgate Residences is a 24-villa gated community designed around a shared green corridor rather than isolated private plots. Each villa was oriented and cross-ventilated to reduce reliance on air conditioning during Lahore's hot months, using deep roof overhangs to shade window openings through peak sun hours.\n\nOur interior design team developed three material palette options that owners could select from during fit-out, all specified for durability under Lahore's climate and dust conditions.\n\nThe development also includes a shared clubhouse and landscaped green corridor connecting all 24 villas, designed to encourage a genuine sense of community rather than simply maximizing plot yield.",
                'milestones' => [
                    ['title' => 'Design & Approvals', 'date' => 'Nov 2021 – May 2022'],
                    ['title' => 'Construction — Phase 1 (12 villas)', 'date' => 'Jun 2022 – Feb 2023'],
                    ['title' => 'Construction — Phase 2 (12 villas)', 'date' => 'Mar 2023 – Jul 2023'],
                    ['title' => 'Handover', 'date' => 'Aug 2023'],
                ],
                'services_involved' => ['Architectural Design', 'Interior Design'],
                'is_featured' => false,
            ],
            [
                'title' => 'Riverside Medical Center',
                'category' => 'Healthcare',
                'client' => 'Riverside Healthcare Trust',
                'location' => 'Multan, Pakistan',
                'completion_date' => '2025-05-18',
                'project_value' => 'PKR 890 Million',
                'area' => '78,000 sq ft',
                'excerpt' => 'A new 60-bed community medical center providing outpatient, diagnostic, and emergency care to underserved communities in Multan.',
                'scope' => 'Full healthcare facility design and construction management for a new-build 60-bed community medical center, including outpatient, diagnostic, and emergency departments.',
                'features' => 'Dedicated emergency department with ambulance-only access, outpatient department designed for high daily patient throughput, on-site diagnostic imaging, and a rooftop solar array offsetting a portion of facility power demand.',
                'content' => "Riverside Medical Center was built from the ground up to serve a community that previously had to travel significant distances for outpatient and emergency care. Our healthcare design team planned the facility around high daily patient throughput in the outpatient department, while keeping the emergency department fully separated with dedicated ambulance access.\n\nGiven budget constraints typical of trust-funded community healthcare projects, we prioritized clinical function over decorative finishes, while still meeting healthcare facility accessibility and infection control standards throughout.\n\nA rooftop solar array was included to offset a portion of the facility's power demand, reducing long-term operating costs for the trust that will run the center.",
                'milestones' => [
                    ['title' => 'Clinical Briefing & Design', 'date' => 'Jun 2023 – Nov 2023'],
                    ['title' => 'Foundation & Structure', 'date' => 'Dec 2023 – Aug 2024'],
                    ['title' => 'MEP & Fit-Out', 'date' => 'Sep 2024 – Mar 2025'],
                    ['title' => 'Commissioning & Handover', 'date' => 'May 2025'],
                ],
                'services_involved' => ['Healthcare Facility Design', 'Construction Management'],
                'is_featured' => true,
            ],
            [
                'title' => 'National Skills Institute Campus',
                'category' => 'Institutional',
                'client' => 'National Skills Foundation',
                'location' => 'Islamabad, Pakistan',
                'completion_date' => '2024-09-12',
                'project_value' => 'PKR 1.1 Billion',
                'area' => '150,000 sq ft',
                'excerpt' => 'A vocational training campus combining classrooms, workshops, and student housing designed for daylight, acoustics, and long-term flexibility.',
                'scope' => 'Architectural design and construction management for a vocational training campus including classroom blocks, technical workshops, and a student residence hall.',
                'features' => 'Daylight-optimized classroom orientation, acoustically isolated technical workshops, flexible classroom partitions for changing program needs, and an on-site student residence hall for 200 students.',
                'content' => "This vocational training campus was designed to serve a technical education program expected to grow and change its curriculum over time, so flexibility was a core design driver from day one. Classrooms use operable partitions that let the institute reconfigure room sizes as enrollment and program needs shift.\n\nTechnical workshops — covering trades from electrical to automotive — were acoustically isolated from classroom blocks so that noisy practical training doesn't disrupt academic instruction elsewhere on campus.\n\nThe campus also includes an on-site residence hall for 200 students, designed with the same daylight and ventilation priorities as the academic buildings.",
                'milestones' => [
                    ['title' => 'Design & Approvals', 'date' => 'Jan 2023 – Jun 2023'],
                    ['title' => 'Academic Blocks Construction', 'date' => 'Jul 2023 – Mar 2024'],
                    ['title' => 'Residence Hall Construction', 'date' => 'Apr 2024 – Aug 2024'],
                    ['title' => 'Handover', 'date' => 'Sep 2024'],
                ],
                'services_involved' => ['Architectural Design', 'Construction Management', 'Project Management'],
                'is_featured' => false,
            ],
        ];

        foreach ($projects as $i => $data) {
            $project = Project::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['title'])],
                [
                    'project_category_id' => $categories[$data['category']]->id,
                    'title' => $data['title'],
                    'client' => $data['client'],
                    'location' => $data['location'],
                    'completion_date' => $data['completion_date'],
                    'project_value' => $data['project_value'],
                    'area' => $data['area'],
                    'scope' => $data['scope'],
                    'features' => $data['features'],
                    'milestones' => $data['milestones'],
                    'services_involved' => $data['services_involved'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'status' => 'published',
                    'is_featured' => $data['is_featured'],
                    'order' => $i,
                ]
            );

            $this->attachImage($project, 'featured_image', 'acco-proj-'.$project->slug, 1600, 1200);
            $this->attachImage($project, 'gallery', 'acco-proj-'.$project->slug.'-g1', 1200, 900);
            $this->attachImage($project, 'gallery', 'acco-proj-'.$project->slug.'-g2', 1200, 900);
            $this->attachImage($project, 'gallery', 'acco-proj-'.$project->slug.'-g3', 1200, 900);
        }

        // ---------------------------------------------------------------
        // Team members
        // ---------------------------------------------------------------
        $team = [
            ['name' => 'Ahmed Khan', 'position' => 'Chief Architect', 'department' => 'Architecture', 'bio' => 'Ahmed leads ACCO\'s architecture studio, with over 18 years designing commercial and institutional buildings across Punjab. He holds a Master of Architecture and is a licensed architect with the Pakistan Council of Architects and Town Planners.'],
            ['name' => 'Sana Malik', 'position' => 'Managing Director', 'department' => 'Executive', 'bio' => 'Sana co-founded ACCO in 2009 and has led the firm\'s growth from a small structural practice into a full design-build company operating across four cities in Pakistan.'],
            ['name' => 'Bilal Raza', 'position' => 'Principal Structural Engineer', 'department' => 'Engineering', 'bio' => 'Bilal oversees all structural engineering at ACCO, with particular expertise in seismic design for high-rise and industrial structures across Pakistan\'s active seismic zones.'],
            ['name' => 'Fatima Malik', 'position' => 'Director of Construction', 'department' => 'Construction', 'bio' => 'Fatima has managed construction delivery on over 60 projects during her career, and now leads ACCO\'s site operations, safety standards, and subcontractor management nationally.'],
            ['name' => 'Usman Tariq', 'position' => 'Head of Interior Design', 'department' => 'Interior Design', 'bio' => 'Usman leads ACCO\'s interior design practice across commercial, hospitality, and residential projects, with a focus on materials suited to Pakistan\'s climate and maintenance realities.'],
            ['name' => 'Dr. Ayesha Siddiqui', 'position' => 'Healthcare Design Lead', 'department' => 'Healthcare', 'bio' => 'Ayesha combines a clinical background with architectural training to lead ACCO\'s healthcare facility design practice, working directly with hospital administrators and clinical staff on every project.'],
            ['name' => 'Hamza Farooq', 'position' => 'Director of Project Management', 'department' => 'Project Management', 'bio' => 'Hamza leads ACCO\'s project management practice, coordinating multidisciplinary teams across design and construction to keep major projects on schedule and on budget.'],
        ];

        foreach ($team as $i => $data) {
            $member = TeamMember::updateOrCreate(
                ['name' => $data['name']],
                [
                    'position' => $data['position'],
                    'department' => $data['department'],
                    'bio' => $data['bio'],
                    'order' => $i,
                    'is_active' => true,
                ]
            );

            $this->attachImage($member, 'photo', 'acco-team-'.\Illuminate\Support\Str::slug($data['name']), 600, 700);
        }

        // ---------------------------------------------------------------
        // Testimonials
        // ---------------------------------------------------------------
        $blueHorizon = Project::where('slug', 'blue-horizon-business-tower')->first();
        $alShifa = Project::where('slug', 'al-shifa-hospital-expansion')->first();
        $meridian = Project::where('slug', 'meridian-business-park')->first();
        $riverside = Project::where('slug', 'riverside-medical-center')->first();

        $testimonials = [
            ['project' => $blueHorizon, 'client_name' => 'Kamran Sheikh', 'client_position' => 'Chief Executive', 'company' => 'Zenith Business Park', 'quote' => 'ACCO delivered Blue Horizon Tower on schedule and within budget, which is rare for a project of this scale. Their construction management team kept us informed every week, not just at milestones.', 'rating' => 5],
            ['project' => $alShifa, 'client_name' => 'Dr. Nadia Hussain', 'client_position' => 'Hospital Administrator', 'company' => 'Al-Shifa Healthcare Trust', 'quote' => 'Building a surgical wing while keeping the rest of the hospital fully operational required real discipline from ACCO\'s team. They understood the clinical stakes and planned construction around our patients, not the other way around.', 'rating' => 5],
            ['project' => $meridian, 'client_name' => 'Omar Chaudhry', 'client_position' => 'Development Director', 'company' => 'Continental Retail Group', 'quote' => 'As our design-build partner, ACCO carried real accountability for the entire program. When issues came up, we had one team to call, not five contractors pointing at each other.', 'rating' => 5],
            ['project' => null, 'client_name' => 'Rabia Aslam', 'client_position' => 'Facilities Director', 'company' => 'Faisalabad Textile Mills', 'quote' => 'The structural team designed our production floor around our actual machine layout instead of handing us a generic industrial shed. That attention to detail saved us real money in the long run.', 'rating' => 4],
            ['project' => $riverside, 'client_name' => 'Dr. Imran Qureshi', 'client_position' => 'Medical Director', 'company' => 'Riverside Healthcare Trust', 'quote' => 'ACCO designed a facility that respected our budget as a trust-funded healthcare provider while still meeting every clinical and safety requirement. Our patients notice the difference.', 'rating' => 5],
            ['project' => null, 'client_name' => 'Farhan Ali', 'client_position' => 'Chairman', 'company' => 'Willowgate Properties', 'quote' => 'Every villa in the Willowgate development stays noticeably cooler than comparable homes nearby, thanks to ACCO\'s passive design approach. Our buyers ask about it constantly.', 'rating' => 5],
        ];

        foreach ($testimonials as $i => $data) {
            Testimonial::updateOrCreate(
                ['client_name' => $data['client_name'], 'company' => $data['company']],
                [
                    'project_id' => $data['project']?->id,
                    'client_position' => $data['client_position'],
                    'quote' => $data['quote'],
                    'rating' => $data['rating'],
                    'order' => $i,
                    'is_active' => true,
                ]
            );
        }

        // ---------------------------------------------------------------
        // Clients
        // ---------------------------------------------------------------
        $clients = [
            'Zenith Business Park', 'Al-Shifa Healthcare Trust', 'Continental Retail Group',
            'Faisalabad Textile Mills', 'Willowgate Properties', 'National Skills Foundation',
            'Riverside Healthcare Trust', 'Metro Health Group',
        ];

        foreach ($clients as $i => $name) {
            Client::updateOrCreate(['name' => $name], ['order' => $i, 'is_active' => true]);
        }

        // ---------------------------------------------------------------
        // Blog posts
        // ---------------------------------------------------------------
        $posts = [
            [
                'title' => 'ACCO Pakistan Completes Al-Shifa Hospital Expansion',
                'category' => 'Company News',
                'excerpt' => 'Our team has delivered a 120-bed expansion to Al-Shifa Healthcare Trust\'s Rawalpindi campus, adding a new surgical wing, isolation ward, and diagnostic imaging department.',
                'content' => "ACCO Pakistan has completed a major expansion of Al-Shifa Healthcare Trust's hospital campus in Rawalpindi, delivering 120 new beds, a four-theatre surgical wing, a negative-pressure isolation ward, and an expanded diagnostic imaging department.\n\nThe project was delivered in two phases over 18 months while the existing hospital remained fully operational, requiring close daily coordination with hospital operations staff to protect patient care throughout construction.\n\n\"Building inside a working hospital is one of the hardest environments in construction,\" said Fatima Malik, ACCO's Director of Construction. \"Every access route, every delivery, and every noisy activity had to be planned around patient care, not just our schedule.\"\n\nThe expansion significantly increases Al-Shifa's surgical and critical care capacity, serving a growing patient population across Rawalpindi and the wider Islamabad region. ACCO's healthcare design team worked directly with the trust's clinical leadership throughout design to ensure the new wing matched real operational workflow.",
                'reading_time' => 4,
                'is_featured' => true,
            ],
            [
                'title' => 'Designing for Pakistan\'s Climate: Passive Cooling in Modern Architecture',
                'category' => 'Sustainability',
                'excerpt' => 'Long before mechanical cooling, architecture in Pakistan relied on orientation, shading, and cross-ventilation to stay livable through extreme heat. Here\'s how we bring those principles into modern buildings.',
                'content' => "Pakistan's summers routinely push past 40 degrees Celsius across much of Punjab and Sindh, and mechanical air conditioning alone is an expensive, energy-intensive way to manage that heat at scale. At ACCO, passive cooling strategies are a starting point in every design brief, not an afterthought.\n\nThe simplest lever is orientation: positioning a building's long facade away from direct east-west sun exposure can meaningfully reduce solar heat gain before any mechanical system is switched on. Deep roof overhangs and vertical shading fins block direct sun from hitting glazing during peak hours while still allowing daylight in.\n\nCross-ventilation is the second major lever. Floor plans designed with openings on opposing facades allow prevailing breezes to move through a building naturally, reducing dependence on air conditioning during shoulder seasons.\n\nOn our Willowgate Residences project, we combined all three strategies — orientation, shading, and cross-ventilation — and owners have reported noticeably lower cooling costs compared to conventional homes in the same area. Passive design doesn't replace mechanical cooling entirely, but it meaningfully reduces the load those systems need to carry, which matters both for energy bills and for Pakistan's strained power grid.",
                'reading_time' => 6,
                'is_featured' => true,
            ],
            [
                'title' => 'Why Design-Build Is Changing Commercial Construction in Pakistan',
                'category' => 'Industry Insights',
                'excerpt' => 'Traditional design-bid-build creates a gap between the architect\'s intent and the contractor\'s execution. Design-build closes that gap — and clients are starting to notice.',
                'content' => "In a traditional design-bid-build project, an owner hires an architect to design a building, then separately tenders construction to the lowest qualified bidder. The problem is accountability: when something goes wrong on site, the architect can point to the contractor's execution, and the contractor can point to gaps in the drawings.\n\nDesign-build collapses that separation. One firm carries responsibility for both design and construction under a single contract, which means the same team that drew the building is accountable for building it correctly, on budget, and on schedule.\n\nOn our Meridian Business Park project, ACCO acted as design-build lead across three interconnected commercial blocks. Because the same engineers who designed the structural system also managed procurement and site sequencing, design changes during construction were resolved in days rather than the weeks a traditional tender process often requires.\n\nDesign-build isn't right for every project — clients who want to competitively tender construction separately still have good reasons to do so. But for clients who want one accountable partner and a faster path from concept to occupancy, it's an increasingly common choice in Pakistan's commercial construction market.",
                'reading_time' => 5,
                'is_featured' => false,
            ],
            [
                'title' => 'Healthcare Design Trends: Patient-Centered Hospital Planning',
                'category' => 'Design Trends',
                'excerpt' => 'Modern hospital design is shifting away from purely clinical efficiency toward layouts that also reduce patient stress and support recovery.',
                'content' => "For decades, hospital design prioritized clinical efficiency above almost everything else — shortest distance between departments, maximum bed density, minimum circulation space. Those priorities haven't disappeared, but they're increasingly balanced against a growing body of evidence that a patient's physical environment affects recovery outcomes.\n\nNatural daylight in patient rooms, for example, is now understood to reduce recovery time and patient-reported pain levels in multiple clinical studies. Clear, intuitive wayfinding reduces stress for patients and visiting families navigating an unfamiliar building during an already difficult time.\n\nOn our Riverside Medical Center project, we designed the outpatient department with a single, legible circulation spine rather than a maze of connecting corridors, specifically to reduce the anxiety patients often feel navigating an unfamiliar healthcare facility.\n\nThe challenge for healthcare architects is balancing these patient-centered priorities against the hard clinical requirements of infection control, staff efficiency, and equipment access — priorities that don't always point in the same direction. Getting that balance right is, increasingly, what separates good healthcare design from merely functional healthcare design.",
                'reading_time' => 5,
                'is_featured' => false,
            ],
            [
                'title' => 'Structural Engineering Considerations for Seismic Zones in Pakistan',
                'category' => 'Industry Insights',
                'excerpt' => 'Much of Pakistan sits in active seismic zones, and structural design has to account for that reality from the earliest concept stage, not as a late-stage compliance check.',
                'content' => "Pakistan sits at the junction of the Indian and Eurasian tectonic plates, and large parts of the country — including Islamabad, Rawalpindi, and much of northern Punjab — fall within moderate to high seismic zones. Structural engineering here has to treat seismic loading as a primary design driver, not a compliance box to check after the architecture is finalized.\n\nReinforced concrete moment-resisting frames remain the most common structural system for mid-rise buildings in Pakistan, but detailing matters enormously: ductile reinforcement detailing at beam-column joints is what allows a structure to deform and absorb seismic energy without catastrophic failure, rather than simply being 'strong' in a static sense.\n\nFor taller structures like Blue Horizon Business Tower, our structural team incorporated shear walls to manage lateral loads that moment frames alone can't efficiently resist at height. Foundation design also has to account for soil conditions specific to each site — the same structural system can require significantly different foundation depths just a few kilometers apart depending on local soil bearing capacity.\n\nGetting seismic design right isn't just a regulatory requirement — it's the difference between a building that protects its occupants during an earthquake and one that doesn't. It's a responsibility we take as seriously as any other part of the design process.",
                'reading_time' => 6,
                'is_featured' => false,
            ],
        ];

        foreach ($posts as $i => $data) {
            $post = BlogPost::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['title'])],
                [
                    'blog_category_id' => $blogCategories[$data['category']]->id,
                    'author_id' => $author?->id,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'status' => 'published',
                    'published_at' => now()->subDays((count($posts) - $i) * 12),
                    'reading_time' => $data['reading_time'],
                    'is_featured' => $data['is_featured'],
                ]
            );

            $this->attachImage($post, 'featured_image', 'acco-blog-'.$post->slug, 1600, 1000);
        }

        // ---------------------------------------------------------------
        // FAQs
        // ---------------------------------------------------------------
        $faqs = [
            ['category' => 'General', 'question' => 'What areas of Pakistan does ACCO operate in?', 'answer' => 'ACCO Pakistan operates nationally from offices in Rawalpindi, Lahore, Karachi, and Faisalabad, with project experience across Punjab, Sindh, and the federal capital region.'],
            ['category' => 'General', 'question' => 'Does ACCO handle both design and construction?', 'answer' => 'Yes. ACCO is a full design-build practice — we can handle architecture, engineering, and construction under one contract, or provide any of these services independently depending on what a client needs.'],
            ['category' => 'General', 'question' => 'How long has ACCO been operating?', 'answer' => 'ACCO Private Limited was founded in 2009 and has grown from a small structural engineering practice into a multidisciplinary architecture, engineering, and construction firm with over 100 completed projects.'],
            ['category' => 'Services', 'question' => 'Can ACCO manage a project designed by another architect?', 'answer' => 'Yes. Our project management and construction management services can be engaged independently to oversee projects designed or partially completed by other firms.'],
            ['category' => 'Services', 'question' => 'Does ACCO design healthcare facilities specifically?', 'answer' => 'Yes, healthcare facility design is a dedicated practice at ACCO, led by team members with direct clinical planning experience across hospitals and diagnostic centers.'],
            ['category' => 'Services', 'question' => 'What size of project does ACCO typically take on?', 'answer' => 'Our project portfolio ranges from single-villa residential projects to multi-building commercial campuses exceeding PKR 3 billion in value. We scope our team to match the project.'],
            ['category' => 'Process', 'question' => 'How long does a typical design phase take?', 'answer' => 'Design timelines vary by project complexity, but a typical commercial building moves from initial brief through construction-ready documentation in 6 to 9 months.'],
            ['category' => 'Process', 'question' => 'How does ACCO handle project cost estimation?', 'answer' => 'We develop a live cost model alongside design development, so clients see budget implications of design decisions in real time rather than receiving a single estimate at the end of design.'],
            ['category' => 'Process', 'question' => 'What happens if a project timeline changes during construction?', 'answer' => 'Our construction management team maintains a live schedule and reports variances weekly, so clients are never surprised by a delay at the point it happens rather than at the next milestone.'],
            ['category' => 'Careers', 'question' => 'Does ACCO offer internships or graduate positions?', 'answer' => 'Yes, we regularly hire graduate architects and engineers into our studios. Open positions are listed on our Careers page as they become available.'],
        ];

        foreach ($faqs as $i => $data) {
            Faq::updateOrCreate(
                ['question' => $data['question']],
                ['answer' => $data['answer'], 'category' => $data['category'], 'order' => $i, 'is_active' => true]
            );
        }

        // ---------------------------------------------------------------
        // Job postings
        // ---------------------------------------------------------------
        $jobs = [
            ['title' => 'Senior Structural Engineer', 'department' => 'Engineering', 'location' => 'Rawalpindi', 'type' => 'full-time', 'description' => "ACCO Pakistan is looking for a Senior Structural Engineer to join our engineering team in Rawalpindi, working across commercial, healthcare, and industrial projects.\n\nYou will lead structural design from concept through construction-ready documentation, working closely with our architecture and MEP teams from the earliest project stages.", 'requirements' => "Bachelor's or Master's degree in Civil/Structural Engineering.\nMinimum 7 years of structural design experience, including seismic design.\nRegistered with the Pakistan Engineering Council.\nProficiency with ETABS, SAP2000, or equivalent structural analysis software."],
            ['title' => 'Project Architect', 'department' => 'Architecture', 'location' => 'Lahore', 'type' => 'full-time', 'description' => "We're hiring a Project Architect to lead design delivery on commercial and institutional projects out of our Lahore studio, from concept design through construction documentation.", 'requirements' => "Bachelor's or Master's degree in Architecture.\nMinimum 5 years of professional experience, ideally on commercial or institutional projects.\nRegistered with the Pakistan Council of Architects and Town Planners.\nStrong proficiency in Revit and AutoCAD."],
            ['title' => 'Healthcare Planning Consultant', 'department' => 'Healthcare', 'location' => 'Rawalpindi', 'type' => 'contract', 'description' => "ACCO is seeking a Healthcare Planning Consultant to support clinical workflow planning on upcoming hospital and clinic projects, working directly with our Healthcare Design Lead.", 'requirements' => "Background in clinical operations, nursing, or healthcare facility planning.\nExperience working alongside architects or engineers on healthcare capital projects preferred.\nStrong communication skills for working directly with hospital administration."],
        ];

        foreach ($jobs as $i => $data) {
            JobPosting::updateOrCreate(
                ['title' => $data['title']],
                [
                    'slug' => \Illuminate\Support\Str::slug($data['title']),
                    'department' => $data['department'],
                    'location' => $data['location'],
                    'type' => $data['type'],
                    'description' => $data['description'],
                    'requirements' => $data['requirements'],
                    'status' => 'open',
                ]
            );
        }

        // ---------------------------------------------------------------
        // Settings: hero, about, and OG images
        // ---------------------------------------------------------------
        $this->settingImage('hero_image', 'homepage', 'acco-hero-main', 1920, 1280);
        $this->settingImage('about_image', 'about', 'acco-about-main', 1200, 1500);
        $this->settingImage('default_og_image', 'seo', 'acco-og-default', 1200, 630);

        if (! Setting::get('intro_content')) {
            Setting::set('intro_content', "ACCO Private Limited is a multidisciplinary architecture, engineering, and construction firm headquartered in Rawalpindi, with studios in Lahore, Karachi, and Faisalabad. Since 2009, we have delivered over 100 projects across commercial, healthcare, industrial, and residential sectors — carrying architecture, engineering, and construction under one accountable roof.", 'about', 'text');
        }

        if (! Setting::get('about_content')) {
            Setting::set('about_content', "We are a design-build practice built on a simple premise: the firm that designs a building should be able to stand behind how it's actually constructed. That's why ACCO brings architecture, structural and MEP engineering, interior design, and construction management together under one team, rather than handing a design off to whoever wins a separate tender.\n\nOur portfolio spans commercial towers, hospitals, manufacturing plants, and private residences across Pakistan, each delivered by the same disciplined process: understand the brief, design with real engineering behind it, and build exactly what was promised.", 'about', 'text');
        }

        if (! Setting::get('mission')) {
            Setting::set('mission', 'To deliver architecture, engineering, and construction of international quality, built responsibly for the climate, codes, and communities of Pakistan.', 'about', 'text');
        }

        if (! Setting::get('vision')) {
            Setting::set('vision', 'To be Pakistan\'s most trusted design-build partner — the firm clients call first when a project genuinely matters.', 'about', 'text');
        }
    }
}
