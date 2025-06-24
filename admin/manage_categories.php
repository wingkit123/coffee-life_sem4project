<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Categories - BeanMarket Admin</title>
  <link rel="stylesheet" href="../home_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .admin-container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #6F4E37;
    }

    .admin-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: linear-gradient(135deg, #6F4E37, #8B4513);
      color: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-number {
      font-size: 2em;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .form-section,
    .table-section {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 10px;
      margin-bottom: 30px;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #6F4E37;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #6F4E37;
    }

    .btn {
      background: #6F4E37;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.3s ease;
      margin: 5px;
    }

    .btn:hover {
      background: #8B4513;
      transform: translateY(-2px);
    }

    .btn-danger {
      background: #dc3545;
    }

    .btn-danger:hover {
      background: #c82333;
    }

    .btn-warning {
      background: #ffc107;
      color: #212529;
    }

    .btn-warning:hover {
      background: #e0a800;
    }

    .search-controls {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
      flex-wrap: wrap;
      align-items: center;
    }

    .search-input {
      flex: 1;
      min-width: 200px;
    }

    .categories-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .categories-table th,
    .categories-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .categories-table th {
      background: #6F4E37;
      color: white;
      font-weight: bold;
    }

    .categories-table tr:hover {
      background: #f5f5f5;
    }

    .status-badge {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
    }

    .status-active {
      background: #d4edda;
      color: #155724;
    }

    .status-inactive {
      background: #f8d7da;
      color: #721c24;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      color: #6F4E37;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .category-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 8px;
    }

    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 15px;
      }

      .search-controls {
        flex-direction: column;
      }

      .categories-table {
        font-size: 14px;
      }

      .categories-table th,
      .categories-table td {
        padding: 10px 8px;
      }
    }
  </style>
</head>

<body>
  <a href="dashboard.php" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
  </a>

  <div class="admin-container">
    <div class="admin-header">
      <h1><i class="fas fa-tags"></i> Manage Product Categories</h1>
      <div>
        <button class="btn" onclick="showAddForm()">
          <i class="fas fa-plus"></i> Add New Category
        </button>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="admin-stats">
      <div class="stat-card">
        <div class="stat-number" id="totalCategories">8</div>
        <div>Total Categories</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="activeCategories">6</div>
        <div>Active Categories</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="productsTotal">24</div>
        <div>Total Products</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="popularCategory">Coffee</div>
        <div>Most Popular</div>
      </div>
    </div>

    <!-- Add/Edit Category Form -->
    <div class="form-section" id="categoryForm" style="display: none;">
      <h3 id="formTitle"><i class="fas fa-plus"></i> Add New Category</h3>
      <form id="categoryFormElement">
        <div class="form-grid">
          <div class="form-group">
            <label for="categoryName">Category Name *</label>
            <input type="text" id="categoryName" name="categoryName" required>
          </div>
          <div class="form-group">
            <label for="categoryStatus">Status</label>
            <select id="categoryStatus" name="categoryStatus">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="categoryDescription">Description</label>
          <textarea id="categoryDescription" name="categoryDescription" rows="3" placeholder="Brief description of the category..."></textarea>
        </div>
        <div class="form-group">
          <label for="categoryImage">Category Image URL</label>
          <input type="url" id="categoryImage" name="categoryImage" placeholder="https://example.com/image.jpg">
        </div>
        <div class="form-group">
          <label for="sortOrder">Sort Order</label>
          <input type="number" id="sortOrder" name="sortOrder" min="1" value="1">
        </div>
        <div style="text-align: right;">
          <button type="button" class="btn btn-warning" onclick="hideForm()">Cancel</button>
          <button type="submit" class="btn" id="submitBtn">
            <i class="fas fa-save"></i> Save Category
          </button>
        </div>
      </form>
    </div>

    <!-- Categories List -->
    <div class="table-section">
      <h3><i class="fas fa-list"></i> Categories List</h3>

      <!-- Search and Filter Controls -->
      <div class="search-controls">
        <div class="form-group search-input">
          <input type="text" id="searchInput" placeholder="Search categories..." onkeyup="filterCategories()">
        </div>
        <div class="form-group">
          <select id="statusFilter" onchange="filterCategories()">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <button class="btn" onclick="filterCategories()">
          <i class="fas fa-search"></i> Filter
        </button>
        <button class="btn btn-warning" onclick="clearFilters()">
          <i class="fas fa-times"></i> Clear
        </button>
      </div>

      <table class="categories-table" id="categoriesTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)">ID <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(1)">Image <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(2)">Name <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(3)">Description <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(4)">Products <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(5)">Status <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(6)">Sort Order <i class="fas fa-sort"></i></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="categoriesTableBody">
          <!-- Sample data - replace with PHP/database integration -->
          <tr>
            <td>1</td>
            <td><img src="../uploads/images/coffee-category.jpg" alt="Coffee" class="category-image" onerror="this.src='https://via.placeholder.com/50x50?text=Coffee'"></td>
            <td>Coffee</td>
            <td>Premium coffee beverages and blends</td>
            <td>12</td>
            <td><span class="status-badge status-active">Active</span></td>
            <td>1</td>
            <td>
              <button class="btn btn-warning" onclick="editCategory(1)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-danger" onclick="deleteCategory(1)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td><img src="../uploads/images/tea-category.jpg" alt="Tea" class="category-image" onerror="this.src='https://via.placeholder.com/50x50?text=Tea'"></td>
            <td>Tea</td>
            <td>Aromatic teas from around the world</td>
            <td>8</td>
            <td><span class="status-badge status-active">Active</span></td>
            <td>2</td>
            <td>
              <button class="btn btn-warning" onclick="editCategory(2)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-danger" onclick="deleteCategory(2)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td><img src="../uploads/images/dessert-category.jpg" alt="Desserts" class="category-image" onerror="this.src='https://via.placeholder.com/50x50?text=Dessert'"></td>
            <td>Desserts</td>
            <td>Sweet treats and pastries</td>
            <td>6</td>
            <td><span class="status-badge status-active">Active</span></td>
            <td>3</td>
            <td>
              <button class="btn btn-warning" onclick="editCategory(3)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-danger" onclick="deleteCategory(3)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td><img src="../uploads/images/snack-category.jpg" alt="Snacks" class="category-image" onerror="this.src='https://via.placeholder.com/50x50?text=Snack'"></td>
            <td>Snacks</td>
            <td>Light bites and savory snacks</td>
            <td>4</td>
            <td><span class="status-badge status-active">Active</span></td>
            <td>4</td>
            <td>
              <button class="btn btn-warning" onclick="editCategory(4)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-danger" onclick="deleteCategory(4)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Sample categories data (replace with AJAX calls to PHP backend)
    let categoriesData = [{
        id: 1,
        name: 'Coffee',
        description: 'Premium coffee beverages and blends',
        products: 12,
        status: 'active',
        sortOrder: 1,
        image: '../uploads/images/coffee-category.jpg'
      },
      {
        id: 2,
        name: 'Tea',
        description: 'Aromatic teas from around the world',
        products: 8,
        status: 'active',
        sortOrder: 2,
        image: '../uploads/images/tea-category.jpg'
      },
      {
        id: 3,
        name: 'Desserts',
        description: 'Sweet treats and pastries',
        products: 6,
        status: 'active',
        sortOrder: 3,
        image: '../uploads/images/dessert-category.jpg'
      },
      {
        id: 4,
        name: 'Snacks',
        description: 'Light bites and savory snacks',
        products: 4,
        status: 'active',
        sortOrder: 4,
        image: '../uploads/images/snack-category.jpg'
      },
      {
        id: 5,
        name: 'Cold Drinks',
        description: 'Refreshing cold beverages',
        products: 10,
        status: 'active',
        sortOrder: 5,
        image: '../uploads/images/cold-drinks-category.jpg'
      },
      {
        id: 6,
        name: 'Hot Drinks',
        description: 'Warming hot beverages',
        products: 15,
        status: 'active',
        sortOrder: 6,
        image: '../uploads/images/hot-drinks-category.jpg'
      },
      {
        id: 7,
        name: 'Seasonal',
        description: 'Limited time seasonal offerings',
        products: 3,
        status: 'inactive',
        sortOrder: 7,
        image: '../uploads/images/seasonal-category.jpg'
      },
      {
        id: 8,
        name: 'Gift Sets',
        description: 'Perfect gifts for coffee lovers',
        products: 5,
        status: 'inactive',
        sortOrder: 8,
        image: '../uploads/images/gift-category.jpg'
      }
    ];

    let editingId = null;

    function showAddForm() {
      document.getElementById('categoryForm').style.display = 'block';
      document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus"></i> Add New Category';
      document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Save Category';
      document.getElementById('categoryFormElement').reset();
      editingId = null;
    }

    function hideForm() {
      document.getElementById('categoryForm').style.display = 'none';
      document.getElementById('categoryFormElement').reset();
      editingId = null;
    }

    function editCategory(id) {
      const category = categoriesData.find(c => c.id === id);
      if (category) {
        document.getElementById('categoryName').value = category.name;
        document.getElementById('categoryDescription').value = category.description;
        document.getElementById('categoryStatus').value = category.status;
        document.getElementById('categoryImage').value = category.image;
        document.getElementById('sortOrder').value = category.sortOrder;

        document.getElementById('categoryForm').style.display = 'block';
        document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Category';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Update Category';
        editingId = id;
      }
    }

    function deleteCategory(id) {
      if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        categoriesData = categoriesData.filter(c => c.id !== id);
        renderTable();
        updateStats();
        alert('Category deleted successfully!');
      }
    }

    function filterCategories() {
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const statusFilter = document.getElementById('statusFilter').value;

      let filtered = categoriesData.filter(category => {
        const matchesSearch = category.name.toLowerCase().includes(searchTerm) ||
          category.description.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || category.status === statusFilter;

        return matchesSearch && matchesStatus;
      });

      renderTable(filtered);
    }

    function clearFilters() {
      document.getElementById('searchInput').value = '';
      document.getElementById('statusFilter').value = '';
      renderTable();
    }

    function sortTable(columnIndex) {
      // Simple sort implementation
      const table = document.getElementById('categoriesTable');
      const tbody = table.querySelector('tbody');
      const rows = Array.from(tbody.querySelectorAll('tr'));

      rows.sort((a, b) => {
        const aText = a.cells[columnIndex].textContent.trim();
        const bText = b.cells[columnIndex].textContent.trim();

        if (!isNaN(aText) && !isNaN(bText)) {
          return parseFloat(aText) - parseFloat(bText);
        }

        return aText.localeCompare(bText);
      });

      rows.forEach(row => tbody.appendChild(row));
    }

    function renderTable(data = categoriesData) {
      const tbody = document.getElementById('categoriesTableBody');
      tbody.innerHTML = '';

      data.forEach(category => {
        const row = document.createElement('tr');
        row.innerHTML = `
                    <td>${category.id}</td>
                    <td><img src="${category.image}" alt="${category.name}" class="category-image" onerror="this.src='https://via.placeholder.com/50x50?text=${category.name}'"></td>
                    <td>${category.name}</td>
                    <td>${category.description}</td>
                    <td>${category.products}</td>
                    <td><span class="status-badge ${category.status === 'active' ? 'status-active' : 'status-inactive'}">${category.status.charAt(0).toUpperCase() + category.status.slice(1)}</span></td>
                    <td>${category.sortOrder}</td>
                    <td>
                        <button class="btn btn-warning" onclick="editCategory(${category.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" onclick="deleteCategory(${category.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
        tbody.appendChild(row);
      });
    }

    function updateStats() {
      const totalCategories = categoriesData.length;
      const activeCategories = categoriesData.filter(c => c.status === 'active').length;
      const totalProducts = categoriesData.reduce((sum, c) => sum + c.products, 0);
      const popularCategory = categoriesData.reduce((max, c) => c.products > max.products ? c : max, categoriesData[0]);

      document.getElementById('totalCategories').textContent = totalCategories;
      document.getElementById('activeCategories').textContent = activeCategories;
      document.getElementById('productsTotal').textContent = totalProducts;
      document.getElementById('popularCategory').textContent = popularCategory ? popularCategory.name : 'N/A';
    }

    // Form submission handler
    document.getElementById('categoryFormElement').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const categoryData = {
        id: editingId || categoriesData.length + 1,
        name: formData.get('categoryName'),
        description: formData.get('categoryDescription'),
        status: formData.get('categoryStatus'),
        image: formData.get('categoryImage') || 'https://via.placeholder.com/50x50?text=' + formData.get('categoryName'),
        sortOrder: parseInt(formData.get('sortOrder')),
        products: 0 // Default for new categories
      };

      if (editingId) {
        const index = categoriesData.findIndex(c => c.id === editingId);
        if (index !== -1) {
          categoriesData[index] = {
            ...categoriesData[index],
            ...categoryData
          };
          alert('Category updated successfully!');
        }
      } else {
        categoriesData.push(categoryData);
        alert('Category added successfully!');
      }

      renderTable();
      updateStats();
      hideForm();
    });

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      renderTable();
      updateStats();
    });
  </script>
</body>

</html>