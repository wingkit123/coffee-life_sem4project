<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products - BeanMarket Admin</title>
  <link rel="stylesheet" href="../home_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .admin-container {
      max-width: 1400px;
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

    .btn-success {
      background: #28a745;
    }

    .btn-success:hover {
      background: #218838;
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

    .products-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .products-table th,
    .products-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .products-table th {
      background: #6F4E37;
      color: white;
      font-weight: bold;
    }

    .products-table tr:hover {
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

    .status-out-of-stock {
      background: #fff3cd;
      color: #856404;
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

    .product-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }

    .price-cell {
      font-weight: bold;
      color: #6F4E37;
    }

    .stock-low {
      color: #dc3545;
      font-weight: bold;
    }

    .image-preview {
      max-width: 200px;
      max-height: 200px;
      margin-top: 10px;
      border-radius: 8px;
      display: none;
    }

    .category-badge {
      background: #e9ecef;
      color: #495057;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
    }

    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 15px;
      }

      .search-controls {
        flex-direction: column;
      }

      .products-table {
        font-size: 14px;
      }

      .products-table th,
      .products-table td {
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
      <h1><i class="fas fa-box"></i> Manage Products</h1>
      <div>
        <button class="btn" onclick="showAddForm()">
          <i class="fas fa-plus"></i> Add New Product
        </button>
        <button class="btn btn-success" onclick="importProducts()">
          <i class="fas fa-upload"></i> Import Products
        </button>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="admin-stats">
      <div class="stat-card">
        <div class="stat-number" id="totalProducts">24</div>
        <div>Total Products</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="activeProducts">20</div>
        <div>Active Products</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="lowStockProducts">3</div>
        <div>Low Stock Alert</div>
      </div>
      <div class="stat-card">
        <div class="stat-number" id="avgPrice">RM 15.50</div>
        <div>Average Price</div>
      </div>
    </div>

    <!-- Add/Edit Product Form -->
    <div class="form-section" id="productForm" style="display: none;">
      <h3 id="formTitle"><i class="fas fa-plus"></i> Add New Product</h3>
      <form id="productFormElement">
        <div class="form-grid">
          <div class="form-group">
            <label for="productName">Product Name *</label>
            <input type="text" id="productName" name="productName" required>
          </div>
          <div class="form-group">
            <label for="productCategory">Category *</label>
            <select id="productCategory" name="productCategory" required>
              <option value="">Select Category</option>
              <option value="Coffee">Coffee</option>
              <option value="Tea">Tea</option>
              <option value="Desserts">Desserts</option>
              <option value="Snacks">Snacks</option>
              <option value="Cold Drinks">Cold Drinks</option>
              <option value="Hot Drinks">Hot Drinks</option>
            </select>
          </div>
          <div class="form-group">
            <label for="productPrice">Price (RM) *</label>
            <input type="number" id="productPrice" name="productPrice" step="0.01" min="0" required>
          </div>
          <div class="form-group">
            <label for="productStock">Stock Quantity *</label>
            <input type="number" id="productStock" name="productStock" min="0" required>
          </div>
          <div class="form-group">
            <label for="productStatus">Status</label>
            <select id="productStatus" name="productStatus">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="out-of-stock">Out of Stock</option>
            </select>
          </div>
          <div class="form-group">
            <label for="productSku">SKU</label>
            <input type="text" id="productSku" name="productSku" placeholder="e.g., COFFEE-001">
          </div>
        </div>
        <div class="form-group">
          <label for="productDescription">Description</label>
          <textarea id="productDescription" name="productDescription" rows="3" placeholder="Product description..."></textarea>
        </div>
        <div class="form-group">
          <label for="productImage">Product Image URL</label>
          <input type="url" id="productImage" name="productImage" placeholder="https://example.com/image.jpg" onchange="previewImage()">
          <img id="imagePreview" class="image-preview" alt="Image preview">
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label for="productSize">Size/Volume</label>
            <input type="text" id="productSize" name="productSize" placeholder="e.g., 250ml, Large">
          </div>
          <div class="form-group">
            <label for="productTemperature">Temperature</label>
            <select id="productTemperature" name="productTemperature">
              <option value="">Select Temperature</option>
              <option value="Hot">Hot</option>
              <option value="Cold">Cold</option>
              <option value="Both">Both</option>
            </select>
          </div>
        </div>
        <div style="text-align: right;">
          <button type="button" class="btn btn-warning" onclick="hideForm()">Cancel</button>
          <button type="submit" class="btn" id="submitBtn">
            <i class="fas fa-save"></i> Save Product
          </button>
        </div>
      </form>
    </div>

    <!-- Products List -->
    <div class="table-section">
      <h3><i class="fas fa-list"></i> Products List</h3>

      <!-- Search and Filter Controls -->
      <div class="search-controls">
        <div class="form-group search-input">
          <input type="text" id="searchInput" placeholder="Search products..." onkeyup="filterProducts()">
        </div>
        <div class="form-group">
          <select id="categoryFilter" onchange="filterProducts()">
            <option value="">All Categories</option>
            <option value="Coffee">Coffee</option>
            <option value="Tea">Tea</option>
            <option value="Desserts">Desserts</option>
            <option value="Snacks">Snacks</option>
            <option value="Cold Drinks">Cold Drinks</option>
            <option value="Hot Drinks">Hot Drinks</option>
          </select>
        </div>
        <div class="form-group">
          <select id="statusFilter" onchange="filterProducts()">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="out-of-stock">Out of Stock</option>
          </select>
        </div>
        <button class="btn" onclick="filterProducts()">
          <i class="fas fa-search"></i> Filter
        </button>
        <button class="btn btn-warning" onclick="clearFilters()">
          <i class="fas fa-times"></i> Clear
        </button>
      </div>

      <table class="products-table" id="productsTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)">ID <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(1)">Image <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(2)">Name <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(3)">Category <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(4)">Price <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(5)">Stock <i class="fas fa-sort"></i></th>
            <th onclick="sortTable(6)">Status <i class="fas fa-sort"></i></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="productsTableBody">
          <!-- Sample data will be populated by JavaScript -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Sample products data (replace with AJAX calls to PHP backend)
    let productsData = [{
        id: 1,
        name: 'Espresso',
        category: 'Coffee',
        price: 8.50,
        stock: 50,
        status: 'active',
        sku: 'COFFEE-001',
        description: 'Rich and bold espresso shot',
        image: '../uploads/images/espresso.jpg',
        size: '30ml',
        temperature: 'Hot'
      },
      {
        id: 2,
        name: 'Cappuccino',
        category: 'Coffee',
        price: 12.00,
        stock: 25,
        status: 'active',
        sku: 'COFFEE-002',
        description: 'Perfect blend of espresso and steamed milk',
        image: '../uploads/images/cappuccino.jpg',
        size: '250ml',
        temperature: 'Hot'
      },
      {
        id: 3,
        name: 'Iced Latte',
        category: 'Coffee',
        price: 14.50,
        stock: 30,
        status: 'active',
        sku: 'COFFEE-003',
        description: 'Smooth espresso with cold milk over ice',
        image: '../uploads/images/iced-latte.jpg',
        size: '350ml',
        temperature: 'Cold'
      },
      {
        id: 4,
        name: 'Green Tea',
        category: 'Tea',
        price: 8.00,
        stock: 40,
        status: 'active',
        sku: 'TEA-001',
        description: 'Refreshing green tea',
        image: '../uploads/images/green-tea.jpg',
        size: '250ml',
        temperature: 'Both'
      },
      {
        id: 5,
        name: 'Chocolate Cake',
        category: 'Desserts',
        price: 18.00,
        stock: 8,
        status: 'active',
        sku: 'DESSERT-001',
        description: 'Rich chocolate layer cake',
        image: '../uploads/images/chocolate-cake.jpg',
        size: '1 slice',
        temperature: ''
      },
      {
        id: 6,
        name: 'Croissant',
        category: 'Snacks',
        price: 6.50,
        stock: 15,
        status: 'active',
        sku: 'SNACK-001',
        description: 'Buttery French croissant',
        image: '../uploads/images/croissant.jpg',
        size: '1 piece',
        temperature: ''
      },
      {
        id: 7,
        name: 'Mocha Frappé',
        category: 'Cold Drinks',
        price: 16.00,
        stock: 2,
        status: 'active',
        sku: 'COLD-001',
        description: 'Iced coffee with chocolate',
        image: '../uploads/images/mocha-frappe.jpg',
        size: '450ml',
        temperature: 'Cold'
      },
      {
        id: 8,
        name: 'Hot Chocolate',
        category: 'Hot Drinks',
        price: 10.00,
        stock: 35,
        status: 'active',
        sku: 'HOT-001',
        description: 'Rich hot chocolate drink',
        image: '../uploads/images/hot-chocolate.jpg',
        size: '300ml',
        temperature: 'Hot'
      },
      {
        id: 9,
        name: 'Decaf Coffee',
        category: 'Coffee',
        price: 9.00,
        stock: 0,
        status: 'out-of-stock',
        sku: 'COFFEE-004',
        description: 'Caffeine-free coffee',
        image: '../uploads/images/decaf.jpg',
        size: '250ml',
        temperature: 'Hot'
      },
      {
        id: 10,
        name: 'Seasonal Blend',
        category: 'Coffee',
        price: 22.00,
        stock: 12,
        status: 'inactive',
        sku: 'COFFEE-005',
        description: 'Limited edition seasonal coffee',
        image: '../uploads/images/seasonal.jpg',
        size: '300ml',
        temperature: 'Both'
      }
    ];

    let editingId = null;

    function showAddForm() {
      document.getElementById('productForm').style.display = 'block';
      document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus"></i> Add New Product';
      document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Save Product';
      document.getElementById('productFormElement').reset();
      document.getElementById('imagePreview').style.display = 'none';
      editingId = null;
    }

    function hideForm() {
      document.getElementById('productForm').style.display = 'none';
      document.getElementById('productFormElement').reset();
      document.getElementById('imagePreview').style.display = 'none';
      editingId = null;
    }

    function previewImage() {
      const imageUrl = document.getElementById('productImage').value;
      const preview = document.getElementById('imagePreview');

      if (imageUrl) {
        preview.src = imageUrl;
        preview.style.display = 'block';
      } else {
        preview.style.display = 'none';
      }
    }

    function editProduct(id) {
      const product = productsData.find(p => p.id === id);
      if (product) {
        document.getElementById('productName').value = product.name;
        document.getElementById('productCategory').value = product.category;
        document.getElementById('productPrice').value = product.price;
        document.getElementById('productStock').value = product.stock;
        document.getElementById('productStatus').value = product.status;
        document.getElementById('productSku').value = product.sku;
        document.getElementById('productDescription').value = product.description;
        document.getElementById('productImage').value = product.image;
        document.getElementById('productSize').value = product.size;
        document.getElementById('productTemperature').value = product.temperature;

        previewImage();

        document.getElementById('productForm').style.display = 'block';
        document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Product';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Update Product';
        editingId = id;
      }
    }

    function deleteProduct(id) {
      if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        productsData = productsData.filter(p => p.id !== id);
        renderTable();
        updateStats();
        alert('Product deleted successfully!');
      }
    }

    function duplicateProduct(id) {
      const product = productsData.find(p => p.id === id);
      if (product) {
        const newProduct = {
          ...product,
          id: Math.max(...productsData.map(p => p.id)) + 1,
          name: product.name + ' (Copy)',
          sku: product.sku + '-COPY'
        };
        productsData.push(newProduct);
        renderTable();
        updateStats();
        alert('Product duplicated successfully!');
      }
    }

    function filterProducts() {
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const categoryFilter = document.getElementById('categoryFilter').value;
      const statusFilter = document.getElementById('statusFilter').value;

      let filtered = productsData.filter(product => {
        const matchesSearch = product.name.toLowerCase().includes(searchTerm) ||
          product.description.toLowerCase().includes(searchTerm) ||
          product.sku.toLowerCase().includes(searchTerm);
        const matchesCategory = !categoryFilter || product.category === categoryFilter;
        const matchesStatus = !statusFilter || product.status === statusFilter;

        return matchesSearch && matchesCategory && matchesStatus;
      });

      renderTable(filtered);
    }

    function clearFilters() {
      document.getElementById('searchInput').value = '';
      document.getElementById('categoryFilter').value = '';
      document.getElementById('statusFilter').value = '';
      renderTable();
    }

    function sortTable(columnIndex) {
      // Simple sort implementation
      const table = document.getElementById('productsTable');
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

    function renderTable(data = productsData) {
      const tbody = document.getElementById('productsTableBody');
      tbody.innerHTML = '';

      data.forEach(product => {
        const row = document.createElement('tr');
        const stockClass = product.stock <= 5 ? 'stock-low' : '';
        const statusClass = product.status === 'active' ? 'status-active' :
          product.status === 'inactive' ? 'status-inactive' : 'status-out-of-stock';

        row.innerHTML = `
                    <td>${product.id}</td>
                    <td><img src="${product.image}" alt="${product.name}" class="product-image" onerror="this.src='https://via.placeholder.com/60x60?text=${product.name}'"></td>
                    <td>
                        <strong>${product.name}</strong><br>
                        <small>SKU: ${product.sku}</small>
                    </td>
                    <td><span class="category-badge">${product.category}</span></td>
                    <td class="price-cell">RM ${product.price.toFixed(2)}</td>
                    <td class="${stockClass}">${product.stock}</td>
                    <td><span class="status-badge ${statusClass}">${product.status.charAt(0).toUpperCase() + product.status.slice(1).replace('-', ' ')}</span></td>
                    <td>
                        <button class="btn btn-warning" onclick="editProduct(${product.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn" onclick="duplicateProduct(${product.id})" title="Duplicate">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button class="btn btn-danger" onclick="deleteProduct(${product.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
        tbody.appendChild(row);
      });
    }

    function updateStats() {
      const totalProducts = productsData.length;
      const activeProducts = productsData.filter(p => p.status === 'active').length;
      const lowStockProducts = productsData.filter(p => p.stock <= 5).length;
      const avgPrice = productsData.reduce((sum, p) => sum + p.price, 0) / totalProducts;

      document.getElementById('totalProducts').textContent = totalProducts;
      document.getElementById('activeProducts').textContent = activeProducts;
      document.getElementById('lowStockProducts').textContent = lowStockProducts;
      document.getElementById('avgPrice').textContent = `RM ${avgPrice.toFixed(2)}`;
    }

    function importProducts() {
      alert('Import feature would integrate with file upload functionality for CSV/Excel files.');
    }

    // Form submission handler
    document.getElementById('productFormElement').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const productData = {
        id: editingId || Math.max(...productsData.map(p => p.id)) + 1,
        name: formData.get('productName'),
        category: formData.get('productCategory'),
        price: parseFloat(formData.get('productPrice')),
        stock: parseInt(formData.get('productStock')),
        status: formData.get('productStatus'),
        sku: formData.get('productSku') || 'AUTO-' + Date.now(),
        description: formData.get('productDescription'),
        image: formData.get('productImage') || 'https://via.placeholder.com/60x60?text=' + formData.get('productName'),
        size: formData.get('productSize'),
        temperature: formData.get('productTemperature')
      };

      if (editingId) {
        const index = productsData.findIndex(p => p.id === editingId);
        if (index !== -1) {
          productsData[index] = {
            ...productsData[index],
            ...productData
          };
          alert('Product updated successfully!');
        }
      } else {
        productsData.push(productData);
        alert('Product added successfully!');
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