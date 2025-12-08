    </main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <h3>Thư viện Đại học Trà Vinh</h3>
                    <p>Hệ thống quản lý thư viện hiện đại</p>
                </div>
                <div>
                    <h3>Liên hệ</h3>
                    <p>📍 Số 126, Nguyễn Thiện Thành, Phường 5, TP. Trà Vinh</p>
                    <p>📞 0294.3855246</p>
                    <p>✉️ library@tvu.edu.vn</p>
                </div>
                <div>
                    <h3>Giờ làm việc</h3>
                    <p>Thứ 2 - Thứ 6: 7:00 - 21:00</p>
                    <p>Thứ 7: 7:00 - 17:00</p>
                    <p>Chủ nhật: Nghỉ</p>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>&copy; <?= date('Y') ?> Đại học Trà Vinh. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="<?= BASE_URL ?>/public/js/main.js?v=<?= time() ?>"></script>
    <script src="<?= BASE_URL ?>/public/js/validation.js?v=<?= time() ?>"></script>
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?= BASE_URL ?>/public/js/<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
