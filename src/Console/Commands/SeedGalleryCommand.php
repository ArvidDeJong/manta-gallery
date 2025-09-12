<?php

namespace Darvis\MantaGallery\Console\Commands;

use Darvis\MantaGallery\Models\Gallery;
use Illuminate\Console\Command;

class SeedGalleryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manta-gallery:seed
                            {--force : Force seeding even if gallerys already exist}
                            {--fresh : Delete existing gallerys before seeding}
                            {--with-navigation : Also seed navigation items for gallery management}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with sample gallerys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌱 Seeding Manta Gallerys...');
        $this->newLine();

        // Check if gallerys already exist
        $existingCount = Gallery::count();

        if ($existingCount > 0 && !$this->option('force') && !$this->option('fresh')) {
            $this->warn("⚠️  Found {$existingCount} existing gallerys.");

            if (!$this->confirm('Do you want to continue seeding? This will add more items.', false)) {
                $this->info('Seeding cancelled.');
                return self::SUCCESS;
            }
        }

        // Handle fresh option
        if ($this->option('fresh')) {
            if ($this->confirm('This will delete ALL existing gallerys. Are you sure?', false)) {
                $this->info('🗑️  Deleting existing gallerys...');
                Gallery::truncate();
                $this->line('   ✅ Existing gallerys deleted');
            } else {
                $this->info('Fresh seeding cancelled.');
                return self::SUCCESS;
            }
        }

        // Run the seeder
        $this->info('📞 Creating sample gallerys...');

        try {
            $this->seedGalleryItems();

            $totalCount = Gallery::count();
            $this->newLine();
            $this->info("🎉 Gallery seeding completed successfully!");
            $this->line("   📊 Total gallerys in database: {$totalCount}");
        } catch (\Exception $e) {
            $this->error('❌ Error during seeding: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Seed navigation if requested
        if ($this->option('with-navigation')) {
            $this->seedNavigation();
        }

        $this->newLine();
        $this->comment('💡 Tips:');
        $this->line('• Use --fresh to start with a clean slate');
        $this->line('• Use --force to skip confirmation prompts');
        $this->line('• Use --with-navigation to also seed navigation items');
        $this->line('• Check your gallery management interface to see the seeded items');

        return self::SUCCESS;
    }

    /**
     * Seed the gallery items into the database
     */
    private function seedGalleryItems(): void
    {
        $galleryItems = [
            [
                'created_by' => 'Gallery Seeder',
                'updated_by' => null,
                'deleted_by' => null,
                'company_id' => 1,
                'host' => request()->getHost() ?? 'localhost',
                'pid' => null,
                'locale' => 'nl',
                'active' => true,
                'sort' => 1,
                'company' => 'Tech Solutions BV',
                'title' => 'Dhr.',
                'sex' => 'M',
                'firstname' => 'Jan',
                'lastname' => 'Jansen',
                'name' => 'Jan Jansen',
                'email' => 'jan.jansen@techsolutions.nl',
                'phone' => '+31 20 123 4567',
                'address' => 'Techniekstraat',
                'address_nr' => '123',
                'zipcode' => '1000 AA',
                'city' => 'Amsterdam',
                'country' => 'Nederland',
                'birthdate' => '1980-05-15',
                'newsletters' => true,
                'subject' => 'Informatie over jullie diensten',
                'comment' => 'Ik ben geïnteresseerd in jullie webdevelopment diensten. Kunnen jullie gallery met mij opnemen?',
                'internal_gallery' => false,
                'ip' => '192.168.1.100',
                'comment_client' => null,
                'comment_internal' => 'Potentiële klant voor webdevelopment project',
                'option_1' => true,
                'option_2' => false,
                'option_3' => false,
                'option_4' => false,
                'option_5' => false,
                'option_6' => false,
                'option_7' => false,
                'option_8' => false,
                'data' => json_encode([
                    'source' => 'website',
                    'interest' => 'webdevelopment',
                    'budget' => '5000-10000'
                ]),
            ],
            [
                'created_by' => 'Gallery Seeder',
                'updated_by' => null,
                'deleted_by' => null,
                'company_id' => 1,
                'host' => request()->getHost() ?? 'localhost',
                'pid' => null,
                'locale' => 'nl',
                'active' => true,
                'sort' => 2,
                'company' => 'Marketing Plus',
                'title' => 'Mevr.',
                'sex' => 'V',
                'firstname' => 'Maria',
                'lastname' => 'van der Berg',
                'name' => 'Maria van der Berg',
                'email' => 'maria@marketingplus.nl',
                'phone' => '+31 30 987 6543',
                'address' => 'Marketingweg',
                'address_nr' => '456',
                'zipcode' => '3500 BB',
                'city' => 'Utrecht',
                'country' => 'Nederland',
                'birthdate' => '1975-11-22',
                'newsletters' => true,
                'subject' => 'Samenwerking mogelijkheden',
                'comment' => 'Wij zijn een marketingbureau en zoeken naar technische partners voor onze klanten. Zouden we kunnen samenwerken?',
                'internal_gallery' => false,
                'ip' => '192.168.1.101',
                'comment_client' => null,
                'comment_internal' => 'Potentiële partner voor marketing projecten',
                'option_1' => false,
                'option_2' => true,
                'option_3' => false,
                'option_4' => false,
                'option_5' => false,
                'option_6' => false,
                'option_7' => false,
                'option_8' => false,
                'data' => json_encode([
                    'source' => 'referral',
                    'interest' => 'partnership',
                    'company_size' => '10-50'
                ]),
            ],
            [
                'created_by' => 'Gallery Seeder',
                'updated_by' => null,
                'deleted_by' => null,
                'company_id' => 1,
                'host' => request()->getHost() ?? 'localhost',
                'pid' => null,
                'locale' => 'nl',
                'active' => true,
                'sort' => 3,
                'company' => null,
                'title' => 'Dhr.',
                'sex' => 'M',
                'firstname' => 'Peter',
                'lastname' => 'de Vries',
                'name' => 'Peter de Vries',
                'email' => 'peter.devries@gmail.com',
                'phone' => '+31 6 12345678',
                'address' => 'Huisstraat',
                'address_nr' => '789',
                'zipcode' => '2000 CC',
                'city' => 'Haarlem',
                'country' => 'Nederland',
                'birthdate' => '1990-03-10',
                'newsletters' => false,
                'subject' => 'Vraag over prijzen',
                'comment' => 'Ik ben een freelancer en heb een website nodig. Wat zijn jullie tarieven voor een eenvoudige website?',
                'internal_gallery' => false,
                'ip' => '192.168.1.102',
                'comment_client' => null,
                'comment_internal' => 'Freelancer, budget beperkt',
                'option_1' => false,
                'option_2' => false,
                'option_3' => true,
                'option_4' => false,
                'option_5' => false,
                'option_6' => false,
                'option_7' => false,
                'option_8' => false,
                'data' => json_encode([
                    'source' => 'google',
                    'interest' => 'website',
                    'budget' => '1000-2500'
                ]),
            ],
            [
                'created_by' => 'Gallery Seeder',
                'updated_by' => null,
                'deleted_by' => null,
                'company_id' => 1,
                'host' => request()->getHost() ?? 'localhost',
                'pid' => null,
                'locale' => 'nl',
                'active' => true,
                'sort' => 4,
                'company' => 'Innovatie Corp',
                'title' => 'Mevr.',
                'sex' => 'V',
                'firstname' => 'Sarah',
                'lastname' => 'Johnson',
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@innovatiecorp.nl',
                'phone' => '+31 40 555 7890',
                'address' => 'Innovatielaan',
                'address_nr' => '321',
                'zipcode' => '5600 DD',
                'city' => 'Eindhoven',
                'country' => 'Nederland',
                'birthdate' => '1985-08-30',
                'newsletters' => true,
                'subject' => 'Custom software ontwikkeling',
                'comment' => 'Wij hebben een custom CRM systeem nodig voor ons bedrijf. Kunnen jullie dit ontwikkelen? We hebben specifieke eisen.',
                'internal_gallery' => false,
                'ip' => '192.168.1.103',
                'comment_client' => null,
                'comment_internal' => 'Grote klant, custom software project',
                'option_1' => true,
                'option_2' => true,
                'option_3' => false,
                'option_4' => false,
                'option_5' => false,
                'option_6' => false,
                'option_7' => false,
                'option_8' => false,
                'data' => json_encode([
                    'source' => 'linkedin',
                    'interest' => 'custom_software',
                    'budget' => '25000+'
                ]),
            ],
            [
                'created_by' => 'Gallery Seeder',
                'updated_by' => null,
                'deleted_by' => null,
                'company_id' => 1,
                'host' => request()->getHost() ?? 'localhost',
                'pid' => null,
                'locale' => 'nl',
                'active' => true,
                'sort' => 5,
                'company' => null,
                'title' => 'Dhr.',
                'sex' => 'M',
                'firstname' => 'Tom',
                'lastname' => 'Bakker',
                'name' => 'Tom Bakker',
                'email' => 'tom.bakker@hotmail.com',
                'phone' => '+31 6 98765432',
                'address' => 'Dorpsstraat',
                'address_nr' => '12',
                'zipcode' => '7500 EE',
                'city' => 'Enschede',
                'country' => 'Nederland',
                'birthdate' => '1992-12-05',
                'newsletters' => false,
                'subject' => 'Website onderhoud',
                'comment' => 'Mijn huidige website heeft onderhoud nodig. Kunnen jullie dit doen? Het gaat om updates en bugfixes.',
                'internal_gallery' => false,
                'ip' => '192.168.1.104',
                'comment_client' => null,
                'comment_internal' => 'Bestaande website, onderhoud nodig',
                'option_1' => false,
                'option_2' => false,
                'option_3' => false,
                'option_4' => true,
                'option_5' => false,
                'option_6' => false,
                'option_7' => false,
                'option_8' => false,
                'data' => json_encode([
                    'source' => 'referral',
                    'interest' => 'maintenance',
                    'current_website' => 'WordPress'
                ]),
            ],
        ];

        $created = 0;
        $existing = 0;

        foreach ($galleryItems as $item) {
            // Check if gallery already exists based on email
            $existingGallery = Gallery::where('email', $item['email'])->first();

            if (!$existingGallery) {
                Gallery::create($item);
                $this->info("   ✅ Gallery '{$item['name']}' created.");
                $created++;
            } else {
                $this->info("   ℹ️  Gallery '{$item['name']}' already exists.");
                $existing++;
            }
        }

        $this->info("   📊 {$created} gallerys created, {$existing} gallerys already existed.");
    }

    /**
     * Seed navigation items by calling the manta:seed-navigation command
     */
    private function seedNavigation(): void
    {
        $this->info('🧭 Seeding navigation items...');

        try {
            // First, call the general manta:seed-navigation command from manta-laravel-flux-cms
            $exitCode = $this->call('manta:seed-navigation', [
                '--force' => true // Always force navigation seeding
            ]);

            if ($exitCode === 0) {
                $this->info('   ✅ General navigation items seeded successfully.');
            } else {
                $this->warn('   ⚠️  General navigation seeding completed with warnings.');
            }

            // Then seed gallery-specific navigation items
            $this->seedGalleryNavigation();
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Navigation seeding failed: ' . $e->getMessage());
            $this->warn('   💡 You can manually run "php artisan manta:seed-navigation" later.');
        }
    }

    /**
     * Seed gallery-specific navigation items
     */
    private function seedGalleryNavigation(): void
    {
        $this->info('📞 Seeding gallery navigation items...');

        try {
            // Check if MantaNav model exists
            if (!class_exists('\Manta\FluxCMS\Models\MantaNav')) {
                $this->warn('   ⚠️  MantaNav model not found. Skipping gallery navigation seeding.');
                return;
            }

            $galleryNavItems = [
                [
                    'title' => 'Galleryen',
                    'route' => 'gallery.list',
                    'sort' => 20,
                    'type' => 'module',
                    'description' => 'Beheer galleryaanvragen'
                ]
            ];

            $MantaNav = '\Manta\FluxCMS\Models\MantaNav';
            $created = 0;
            $existing = 0;

            foreach ($galleryNavItems as $item) {
                // Check if navigation item already exists
                $existingNav = $MantaNav::where('route', $item['route'])
                    ->where('locale', 'nl')
                    ->first();

                if (!$existingNav) {
                    $MantaNav::create([
                        'created_by' => 'Gallery Seeder',
                        'updated_by' => null,
                        'deleted_by' => null,
                        'company_id' => 1, // Default company
                        'host' => request()->getHost() ?? 'localhost',
                        'pid' => null,
                        'locale' => 'nl',
                        'active' => true,
                        'sort' => $item['sort'],
                        'title' => $item['title'],
                        'route' => $item['route'],
                        'url' => null,
                        'type' => $item['type'],
                        'rights' => null,
                        'data' => json_encode([
                            'description' => $item['description'],
                            'icon' => 'envelope',
                            'module' => 'manta-gallery'
                        ]),
                    ]);

                    $this->info("   ✅ Gallery navigation item '{$item['title']}' created.");
                    $created++;
                } else {
                    $this->info("   ℹ️  Gallery navigation item '{$item['title']}' already exists.");
                    $existing++;
                }
            }

            $this->info("   📊 {$created} items created, {$existing} items already existed.");
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Gallery navigation seeding failed: ' . $e->getMessage());
            $this->warn('   💡 This may be due to missing MantaNav model or database table.');
        }
    }
}
