@extends('layouts.app')

@section('title', 'FAQs Management')
@section('page-title', 'FAQs Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Frequently Asked Questions</h2>
            <button type="button" class="btn-primary" onclick="openModal('addFaqModal')">
                <i class="fas fa-plus"></i> Add New FAQ
            </button>
        </div>

        <div class="search-box">
            <input type="text" class="search-input" id="faqSearch" placeholder="Search FAQs...">
            <select class="search-input" id="categoryFilter" style="flex: 0 0 200px;">
                <option value="">All Categories</option>
                <option value="general">General</option>
                <option value="billing">Billing</option>
                <option value="support">Support</option>
            </select>
            <button class="btn-secondary btn-sm" onclick="applyFilters()">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="faq-checkbox"></td>
                        <td>How do I create an account?</td>
                        <td><span class="badge badge-info">General</span></td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>2024-01-15</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-secondary btn-sm" onclick="editFaq(1)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-danger btn-sm" onclick="deleteFaq(1)">test
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="faq-checkbox"></td>
                        <td>What payment methods do you accept?</td>
                        <td><span class="badge badge-warning">Billing</span></td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>2024-01-12</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-secondary btn-sm" onclick="editFaq(2)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-danger btn-sm" onclick="deleteFaq(2)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="faq-checkbox"></td>
                        <td>How can I reset my password?</td>
                        <td><span class="badge badge-success">Support</span></td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>2024-01-10</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-secondary btn-sm" onclick="editFaq(3)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-danger btn-sm" onclick="deleteFaq(3)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit FAQ Modal -->
    <div id="addFaqModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="faqModalTitle">Add New FAQ</h2>
                <button type="button" class="modal-close">&times;</button>
            </div>
            <form id="faqForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="faqQuestion">Question *</label>
                        <input type="text" id="faqQuestion" name="question" required>
                    </div>

                    <div class="form-group">
                        <label for="faqAnswer">Answer *</label>
                        <textarea id="faqAnswer" name="answer" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="faqCategory">Category *</label>
                        <select id="faqCategory" name="category" required>
                            <option value="">Select Category</option>
                            <option value="general">General</option>
                            <option value="billing">Billing</option>
                            <option value="support">Support</option>
                            <option value="technical">Technical</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="faqOrder">Display Order</label>
                        <input type="number" id="faqOrder" name="order" value="0">
                    </div>

                    <div class="form-group">
                        <label for="faqStatus">Status</label>
                        <select id="faqStatus" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('addFaqModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Save FAQ</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function editFaq(id) {
            document.getElementById('faqModalTitle').textContent = 'Edit FAQ';
            openModal('addFaqModal');
        }

        function deleteFaq(id) {
            if (confirm('Are you sure you want to delete this FAQ?')) {
                alert('FAQ deleted successfully!');
            }
        }

        function toggleSelectAll(checkbox) {
            document.querySelectorAll('.faq-checkbox').forEach(function(el) {
                el.checked = checkbox.checked;
            });
        }

        function applyFilters() {
            alert('Filters applied!');
        }

        document.getElementById('faqForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('FAQ saved successfully!');
            closeModal('addFaqModal');
            this.reset();
        });

        document.getElementById('addFaqModal').addEventListener('click', function(e) {
            if (e.target === this) {
                document.getElementById('faqForm').reset();
                document.getElementById('faqModalTitle').textContent = 'Add New FAQ';
            }
        });
    </script>
@endpush
