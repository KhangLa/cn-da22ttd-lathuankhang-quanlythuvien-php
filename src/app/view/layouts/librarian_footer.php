    </main>
    
    <footer class="admin-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Thư viện Đại học Trà Vinh. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>
