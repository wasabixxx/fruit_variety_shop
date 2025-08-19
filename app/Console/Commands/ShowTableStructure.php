<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ShowTableStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:table-structure {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show table structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        
        if (!Schema::hasTable($table)) {
            $this->error("Table '{$table}' does not exist!");
            return;
        }
        
        $columns = Schema::getColumnListing($table);
        $this->info("Columns in '{$table}' table:");
        
        foreach ($columns as $column) {
            $this->line("- {$column}");
        }
        
        // Check if email_verified_at exists
        if (Schema::hasColumn($table, 'email_verified_at')) {
            $this->info("\n✅ email_verified_at column EXISTS");
        } else {
            $this->error("\n❌ email_verified_at column MISSING");
        }
        
        // Show sample data
        $this->info("\nSample data:");
        $results = DB::table($table)->limit(5)->get();
        foreach ($results as $row) {
            $this->line(json_encode($row, JSON_PRETTY_PRINT));
            $this->line('---');
        }
    }
}
