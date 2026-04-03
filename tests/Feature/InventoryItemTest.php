<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Lab;
use App\Services\InventoryService;
use Tests\TestCase;

class InventoryItemTest extends TestCase
{
    protected $user;
    protected $lab;
    protected $inventoryService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->lab = Lab::factory()->create();
        $this->user = User::factory()->create(['lab_id' => $this->lab->id]);
        $this->actingAs($this->user);
        
        // Set lab context
        $this->app->instance('current_lab_id', $this->lab->id);
        
        $this->inventoryService = new InventoryService();
    }

    public function test_can_create_inventory_item()
    {
        $supplier = Supplier::factory()->create(['lab_id' => $this->lab->id]);
        
        $response = $this->post(route('inventory.items.store'), [
            'name' => 'Test Kit A',
            'sku' => 'TK-001',
            'category' => 'Test Kit',
            'unit' => 'box',
            'current_stock' => 100,
            'minimum_stock' => 20,
            'unit_cost' => 50.00,
            'preferred_supplier_id' => $supplier->id,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('inventory.items.index'));
        $this->assertDatabaseHas('inventory_items', [
            'name' => 'Test Kit A',
            'sku' => 'TK-001',
            'lab_id' => $this->lab->id,
        ]);
    }

    public function test_can_update_inventory_item()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'name' => 'Original Name',
        ]);

        $response = $this->put(route('inventory.items.update', $item), [
            'name' => 'Updated Name',
            'unit' => $item->unit,
            'minimum_stock' => $item->minimum_stock,
            'unit_cost' => $item->unit_cost,
        ]);

        $response->assertRedirect(route('inventory.items.index'));
        $this->assertDatabaseHas('inventory_items', [
            'id' => $item->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_inventory_item()
    {
        $item = InventoryItem::factory()->create(['lab_id' => $this->lab->id]);

        $response = $this->delete(route('inventory.items.destroy', $item));

        $response->assertRedirect(route('inventory.items.index'));
        $this->assertDatabaseMissing('inventory_items', ['id' => $item->id]);
    }

    public function test_can_add_stock()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 100,
        ]);

        $response = $this->post(route('inventory.items.add-stock', $item), [
            'quantity' => 50,
            'unit_cost' => 45.00,
            'reference_number' => 'INV-001',
            'reason' => 'Stock received from supplier',
        ]);

        $response->assertRedirect(route('inventory.items.show', $item));
        $item->refresh();
        $this->assertEquals(150, $item->current_stock);
    }

    public function test_can_remove_stock()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 100,
        ]);

        $response = $this->post(route('inventory.items.remove-stock', $item), [
            'quantity' => 30,
            'reason' => 'Used for tests',
        ]);

        $response->assertRedirect(route('inventory.items.show', $item));
        $item->refresh();
        $this->assertEquals(70, $item->current_stock);
    }

    public function test_cannot_remove_more_stock_than_available()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 100,
        ]);

        $response = $this->post(route('inventory.items.remove-stock', $item), [
            'quantity' => 150,
            'reason' => 'Test',
        ]);

        $response->assertSessionHasErrors();
        $item->refresh();
        $this->assertEquals(100, $item->current_stock);
    }

    public function test_can_adjust_stock()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 100,
        ]);

        $response = $this->post(route('inventory.items.adjust-stock', $item), [
            'new_quantity' => 75,
            'reason' => 'Physical count adjustment',
        ]);

        $response->assertRedirect(route('inventory.items.show', $item));
        $item->refresh();
        $this->assertEquals(75, $item->current_stock);
    }

    public function test_low_stock_alert_created()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 100,
            'minimum_stock' => 50,
        ]);

        // Remove stock to trigger low stock alert
        $this->inventoryService->removeStock($item, 60, 'Test');

        $this->assertDatabaseHas('stock_alerts', [
            'inventory_item_id' => $item->id,
            'alert_type' => 'low_stock',
            'is_resolved' => false,
        ]);
    }

    public function test_out_of_stock_alert_created()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 10,
        ]);

        // Remove all stock
        $this->inventoryService->removeStock($item, 10, 'Test');

        $this->assertDatabaseHas('stock_alerts', [
            'inventory_item_id' => $item->id,
            'alert_type' => 'out_of_stock',
            'is_resolved' => false,
        ]);
    }

    public function test_stock_movement_created_on_add()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 100,
        ]);

        $movement = $this->inventoryService->addStock($item, 50, 45.00, 'INV-001', 'Received');

        $this->assertDatabaseHas('stock_movements', [
            'inventory_item_id' => $item->id,
            'type' => 'in',
            'quantity' => 50,
            'stock_before' => 100,
            'stock_after' => 150,
        ]);
    }

    public function test_stock_movement_created_on_remove()
    {
        $item = InventoryItem::factory()->create([
            'lab_id' => $this->lab->id,
            'current_stock' => 100,
        ]);

        $movement = $this->inventoryService->removeStock($item, 30, 'Used');

        $this->assertDatabaseHas('stock_movements', [
            'inventory_item_id' => $item->id,
            'type' => 'out',
            'quantity' => 30,
            'stock_before' => 100,
            'stock_after' => 70,
        ]);
    }
}
