<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\PurchaseOrder;
use App\Models\Requisition;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApprovalController extends Controller
{
    /**
     * Models that are allowed to go through the approval workflow.
     * The client sends the short "morph alias" (e.g. "requisition"),
     * never a raw class name — this is what makes the lookup safe.
     */
    private const ALLOWED_TYPES = ['requisition', 'purchase_order'];

    public function process(Request $request)
    {
        $validated = $request->validate([
            'approvable_type' => 'required|string|in:' . implode(',', self::ALLOWED_TYPES),
            'approvable_id'   => 'required|integer',
            'status'          => 'required|in:approved,rejected',
            'comment'         => 'nullable|string|max:2000',
        ]);

        // Resolve the alias through the morph map, never through the
        // client-supplied string directly. If the alias isn't registered
        // this throws, so there is no path to an arbitrary class.
        $modelClass = Relation::getMorphedModel($validated['approvable_type']);

        if (! $modelClass || ! in_array($modelClass, [Requisition::class, PurchaseOrder::class], true)) {
            abort(422, 'Invalid approvable type.');
        }

        $model = $modelClass::findOrFail($validated['approvable_id']);

        $this->authorizeApproval($model);

        Approval::create([
            'approvable_type' => $validated['approvable_type'],
            'approvable_id'   => $validated['approvable_id'],
            'user_id'         => auth()->id(),
            'status'          => $validated['status'],
            'comment'         => $validated['comment'] ?? null,
        ]);

        $model->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Action processed successfully.');
    }

    /**
     * Guard against self-approval and require the approver role.
     */
    private function authorizeApproval($model): void
    {
        $user = auth()->user();

        if (! $user || ! $user->can('approve-items')) {
            abort(403, 'You are not authorized to approve or reject this item.');
        }

        $submitterId = $model instanceof Requisition ? $model->user_id : $model->created_by;

        if ($submitterId === $user->id) {
            throw ValidationException::withMessages([
                'approvable_id' => 'You cannot approve or reject an item you submitted yourself.',
            ]);
        }
    }
}