<template>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Locate Optimal Path</h5>
              <form @submit.prevent="handleSubmit">
                <div class="form-group mb-2">
                  <label class="form-label">Number of stops</label>
                  <input v-model="numOfPaths" class="form-control" min="3" type="number" placeholder="Enter the number of stops" required>
                </div>
                <div class="form-group mb-2">
                  <label class="form-label">Optimise on:</label>
                  <select v-model="optimize" class="form-control" required>
                    <option value="">Select</option>
                    <option value="duration">Duration</option>
                    <option value="distance">Distance</option>
                  </select>
                </div>
                <div class="form-group mb-2" id="locationInputs">
                  <label class="form-label">Starting Location</label>
                  <div v-for="(location, index) in numOfPaths" :key="index">
                    <input :ref="'input' + index" v-model="locations[index]" class="form-control location-input" type="text" :placeholder="'Enter your address for Location ' + (index + 1)" required>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Swal from 'sweetalert2';
  
  export default {
    data() {
      return {
        numOfPaths: 3,
        optimize: '',
        locations: [],
      };
    },
    watch: {
      numOfPaths(newVal) {
        this.locations = Array(newVal).fill('');
        this.$nextTick(() => {
          this.initializeAutocomplete();
        });
      },
    },
    mounted() {
      this.initializeAutocomplete();
    },
    methods: {
      initializeAutocomplete() {
        for (let i = 0; i < this.numOfPaths; i++) {
          let input = this.$refs['input' + i];
          if (input) {
            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
              // Handle place changed event
            });
          }
        }
      },
      handleSubmit() {
        const data = {
          locations: this.locations,
          optimize: this.optimize,
        };
  
        Swal.fire({
          title: 'Processing...',
          html: 'Please wait while we calculate the optimal path.',
          allowOutsideClick: false,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
        });
  
        fetch('/deriveTSP', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify(data),
        })
          .then((response) => response.json())
          .then((data) => {
            Swal.close();
            Swal.fire({
              icon: 'success',
              title: 'Optimal path result',
              html: `<p>${data.optimalPath}</p><p>Total Weight: ${data.totalWeight}</p>`,
            });
          })
          .catch((error) => {
            Swal.close();
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: `Error: ${error.message}`,
            });
            console.error('Error:', error.message);
          });
      },
    },
  };
  </script>
  
  <style scoped>
  .location-input {
    margin-bottom: 10px;
  }
  </style>
  