<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<!-- modal.php -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-confirm">Delete</button>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    var userId; // Store user ID here
    var row; // Store the row that needs to be deleted

    $('.delete-btn').on('click', function(e) {
      e.preventDefault();
      userId = $(this).data('id');
      row = $(this).closest('tr');
      $('#deleteModal').modal('show'); // Show the confirmation modal
    });

    $('.delete-confirm').on('click', function() {
      $.ajax({
        url: 'delete_user.php',
        type: 'POST',
        data: {
          user_id: userId
        },
        success: function(response) {
          if (response.trim() === 'success') {
            row.remove(); // Remove the row from the table
            alert('User deleted successfully.');
          } else {
            alert('An error occurred while deleting the user.');
          }
          $('#deleteModal').modal('hide'); // Hide the modal
        },
        error: function() {
          alert('An error occurred while communicating with the server.');
          $('#deleteModal').modal('hide'); // Hide the modal
        }
      });
    });
  });
</script>