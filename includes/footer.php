    </main>
    <footer class="bg-light text-center py-3 border-top mt-auto">
      <div class="container">
        <small>&copy; <?php echo date('Y'); ?> Alina Computer Shop</small>
      </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// DARK MODE LOGIC
const body = document.body;
const toggleBtn = document.getElementById('darkModeToggle');

// Load theme from storage
if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    toggleBtn.textContent = "🌞";
}

toggleBtn?.addEventListener('click', () => {
    body.classList.toggle('dark-mode');

    if (body.classList.contains('dark-mode')) {
        toggleBtn.textContent = "🌞";
        localStorage.setItem('darkMode', 'enabled');
    } else {
        toggleBtn.textContent = "🌙";
        localStorage.setItem('darkMode', 'disabled');
    }
});
</script>
</body>
</html>

  </body>
</html>
