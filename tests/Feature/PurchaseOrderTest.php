<?php

namespace Tests\Feature;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\InventoryItem;
use App\Models\User;
use App\Models\Lab;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $lab;
    protected $supplier;
    protected $items;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->lab = Lab::factory()->create();
        $this->user = User::factory()->create(['lab_id' => $this->lab->id]);
        $this->actingAs($this->user);
        
        $this->app->instance('current_lab_id', $this->lab->id);
        
        $this->supplier = Supplier::factory()->create(['lab_id' => $this->lab->id]);
        $this->items = InventoryItem::factory()->count(3)->create(['lab_id' => $this->lab->id]);
    }

    public function test_can_create_purchase_order()
    {
        $response = $this->post(route('inventory.purchase-orders.store'), [
            'supplier_id' => $this->supplier->id,
            'order_date' => now()->format('Y-m-d'),
            'expected_delivery_date' => now()->addDays(7)->format('Y-m-d'),
            'items' => [
                [
                    'inventory_item_id' => $this->items[0]->id,
                    'quantity_ordered' => 100,
                    'unit_cost' => 50.00,
                    'discount' => 0,
                    'tax_rate' => 0,
                ],
                [
                    'inventory_item_id' => $this->items[1]->id,
                    'quantity_ordered' => 50,
                    'unit_cost' => 75.00,
                    'discount' => 0,
                    'tax_rate' => 0,
                ],
            ],
            'tax_amount' => 0,
            'shipping_cost' => 0,
            'discount' => 0,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('purchase_orders', [
            'supplier_id' => $this->supplier->id,
            'lab_id' => $this->lab->id,
            'status' => 'draft',
        ]);
    }

    public function test_can_receive_purchase_order()
    {
        $po = PurchaseOrder::factory()->create([
            'lab_id' => $this->lab->id,
            'supplier_id' => $this->supplier->id,
            'status' => 'ordered',
        ]);

        $item1 = PurchaseOrderItem::factory()->create([
            'purchase_order_id' => $po->id,
            'inventory_item_id' => $this->items[0]->id,
            'quantity_ordered' => 100,
            'quantity_received' => 0,
            'unit_cost' => 50.00,
        ]);

        $item2 = PurchaseOrderItem::factory()->create([
            'purchase_order_id' => $po->id,
            'inventory_item_id' => $this->items[1]->id,
            'quantity_ordered' => 50,
            'quantity_received' => 0,
            'unit_cost' => 75.00,
        ]);

        $initialStock1 = $this->items[0]->current_stock;
        $initialStock2 = $this->items[1]->current_stock;

        $response = $this->post(route('inventory.purchase-orders.receive', $po), [
            'items' => [
                ['id' => $item1->id, 'quantity_received' => 100],
                ['id' => $item2->id, 'quantity_received' => 50],
            ],
        ]);

        $response->assertRedirect();
        
        $this->items[0]->refresh();
        $this->items[1]->refresh();
        
        $this->assertEquals($initialStock1 + 100, $this->items[0]->current_stock);
        $this->assertEquals($initialStock2 + 50, $this->items[1]->current_stock);
        
        $po->refresh();
        $this->assertEquals('received', $po->status);
    }

    public function test_can_receive_partial_purchase_order()
    {
        $po = PurchaseOrder::factory()->create([
            'lab_id' => $this->lab->id,
            'supplier_id' => $this->supplier->id,
            'status' => 'ordered',
        ]);

        $item = PurchaseOrderItem::factory()->create([
            'purchase_order_id' => $po->id,
            'inventory_item_id' => $this->items[0]->id,
            'quantity_ordered' => 100,
            'quantity_received' => 0,
            'unit_cost' => 50.00,
        ]);

        $response = $this->post(route('inventory.purchase-orders.receive', $po), [
            'items' => [
                ['id' => $item->id, 'quantity_received' => 50],
            ],
        ]);

        $response->assertRedirect();
        
        $po->refresh();
        $this->assertEquals('partial', $po->status);
        
        $item->refresh();
        $this->assertEquals(50, $item->quantity_received);
    }

    public function test_can_update_draft_purchase_order()
    {
        $po = PurchaseOrder::factory()->create([
            'lab_id' => $this->lab->id,
            'supplier_id' => $this->supplier->id,
            'status' => 'draft',
        ]);

        $newSupplier = Supplier::factory()->create(['lab_id' => $this->lab->id]);

        $response = $this->put(route('inventory.purchase-orders.update', $po), [
            'supplier_id' => $newSupplier->id,
            'order_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'inventory_item_id' => $this->items[0]->id,
                    'quantity_ordered' => 200,
                    'unit_cost' => 60.00,
                    'discount' => 0,
                    'tax_rate' => 0,
                ],
            ],
            'tax_amount' => 0,
            'shipping_cost' => 0,
            'discount' => 0,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('purchase_orders', [
            'id' => $po->id,
            'supplier_id' => $newSupplier->id,
        ]);
    }

    public function test_cannot_update_non_draft_purchase_order()
    {
        $po = PurchaseOrder::factory()->create([
            'lab_id' => $this->lab->id,
            'supplier_id' => $this->supplier->id,
            'status' => 'ordered',
        ]);

        $response = $this->put(route('inventory.purchase-orders.update', $po), [
            'supplier_id' => $this->supplier->id,
            'order_date' => now()->format('Y-m-d'),
            'items' => [],
        ]);

        $response->assertStatus(404);
    }

    public function test_purchase_order_generates_unique_po_number()
    {
        $po1 = PurchaseOrder::factory()->create([
            'lab_id' => $this->lab->id,
            'po_number' => PurchaseOrder::generatePONumber(),
        ]);

        $po2 = PurchaseOrder::factory()->create([
            'lab_id' => $this->lab->id,
            'po_number' => PurchaseOrder::generatePONumber(),
        ]);

        $this->assertNotEquals($po1->po_number, $po2->po_number);
    }
}
