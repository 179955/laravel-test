<template>
    <div class="container mt-5">
    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input
          type="text"
          class="form-control"
          id="name"
          v-model="form.name"
          @blur="v$.form.name.$touch()"
        />
        <div class="text-danger" v-for="error of v$.form.name.$errors" :key="error.$uid">
            <span>{{ error.$message }}</span>
        </div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          class="form-control"
          id="email"
          v-model="form.email"
          @blur="v$.form.email.$touch()"
        />
        <div class="text-danger" v-for="error of v$.form.email.$errors" :key="error.$uid">
            <span>{{ error.$message }}</span>
        </div>
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input
          type="text"
          class="form-control"
          id="phone"
          v-model="form.phone"
          @blur="v$.form.phone.$touch()"
        />
        <div class="text-danger" v-for="error of v$.form.phone.$errors" :key="error.$uid">
            <span>{{ error.$message }}</span>
        </div>
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea
          class="form-control"
          id="message"
          v-model="form.message"
          @blur="v$.form.message.$touch()"
        ></textarea>
        <div class="text-danger" v-for="error of v$.form.message.$errors" :key="error.$uid">
            <span>{{ error.$message }}</span>
        </div>
      </div>

      <div class="mb-3">
        <label for="street" class="form-label">Street</label>
        <input
          type="text"
          class="form-control"
          id="street"
          v-model="form.street"
        />
      </div>

      <div class="mb-3">
        <label for="state" class="form-label">State</label>
        <input
          type="text"
          class="form-control"
          id="state"
          v-model="form.state"
        />
      </div>

      <div class="mb-3">
        <label for="zip" class="form-label">Zip</label>
        <input
          type="text"
          class="form-control"
          id="zip"
          v-model="form.zip"
        />
      </div>

      <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <input
          type="text"
          class="form-control"
          id="country"
          v-model="form.country"
        />
      </div>

      <div class="mb-3">
        <label for="images" class="form-label">Images (JPG only)</label>
        <input
          type="file"
          class="form-control"
          id="images"
          @change="validateImage"
          multiple
        />
        <div v-if="imageError" class="text-danger">{{ imageError }}</div>
      </div>

      <div class="mb-3">
        <label for="files" class="form-label">Files (PDF only)</label>
        <input
          type="file"
          class="form-control"
          id="files"
          @change="validateFile"
          multiple
        />
        <div v-if="fileError" class="text-danger">{{ fileError }}</div>
      </div>
      <button type="submit" class="btn btn-primary" :disabled="v$.$invalid">Submit</button>
    </form>
  </div>
</template>

<style scoped>
.text-danger {
  font-size: 0.875rem;
}
</style>

<script>
import { required, minLength, maxLength, email, helpers } from '@vuelidate/validators';
import useVuelidate from '@vuelidate/core';

export default {
  setup() {
      return { v$: useVuelidate() };
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        message: '',
        street: '',
        state: '',
        zip: '',
        country: '',
        images: null,
        files: null,
      },
      imageError: '',
      fileError: '',
    };
  },
  validations() {
    return {
        form: {
          name: { required, minLength: minLength(2), maxLength: maxLength(10) },
          email: {
            required,
            email,
            validEmail: helpers.withMessage('Email domain must be valid.', (value) => {
              const domain = value.split('@')[1];
              return domain && domain.includes('.');
            }),
            notGmail: helpers.withMessage('Gmail addresses are not allowed.', (value) => {
              return !value.includes('@gmail.com');
            }),
          },
          phone: {
            required,
            validPrefix: helpers.withMessage('Phone must start with a valid prefix.', (value) => {
              return value.startsWith('+') || value.startsWith('0');
            }),
          },
          message: { required, minLength: minLength(10) },
          street: { required },
          state: { required },
          zip: { required },
          country: { required },
        }
    };
  },
  methods: {
    validateImage(event) {
      const files = event.target.files;
      this.imageError = '';
      for (let i = 0; i < files.length; i++) {
        if (!files[i].name.endsWith('.jpg')) {
          this.imageError = 'Only JPG images are allowed.';
          break;
        }
      }
      this.form.images = files;
    },
    validateFile(event) {
      const files = event.target.files;
      this.fileError = '';
      for (let i = 0; i < files.length; i++) {
        if (!files[i].name.endsWith('.pdf')) {
          this.fileError = 'Only PDF files are allowed.';
          break;
        }
      }
      this.form.files = files;
    },
    submitForm() {
      if (this.v$.$invalid) {
        return;
      }
      // TODO: submit the form
      this.resetForm();
    },
    resetForm() {
      this.form = {
        name: '',
        email: '',
        phone: '',
        message: '',
        street: '',
        state: '',
        zip: '',
        country: '',
        images: null,
        files: null,
      };
      this.imageError = '';
      this.fileError = '';
      this.v$.$reset();
    },
  },
};
</script>
