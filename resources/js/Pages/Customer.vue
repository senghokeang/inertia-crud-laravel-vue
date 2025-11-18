<template>
    <div>
        <!-- Delete Modal -->
        <div class="modal fade" ref="deleteModal" tabindex="-1" aria-hidden="true" data-bs-keyboard="false"
            data-bs-backdrop="static" data-bs-focus="false" style="z-index: 1055;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger fs-4 py-2 text-white">
                        <i class="bi bi-x-circle"></i> DELETE
                    </div>
                    <div class="modal-body text-center fs-4">
                        Are you sure want to delete?
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> NO
                        </button>
                        <Link method="delete" :href="deleteURL" class="btn btn-primary px-3" data-bs-dismiss="modal"
                            preserve-state replace preserve-scroll as="button">
                        <i class="bi bi-check-lg"></i> YES
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <!-- Success Modal -->
        <div class="modal fade" ref="successModal" tabindex="-1" aria-hidden="true" data-bs-keyboard="false"
            data-bs-focus="false" style="z-index: 1056;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success fs-4 py-2 text-white">
                        <i class="bi bi-check-circle"></i> SUCCESS
                    </div>
                    <div class="modal-body text-center fs-5">
                        Your data has been saved successfully
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary px-3" data-bs-dismiss="modal">
                            <i class="bi bi-check-lg"></i> OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form Modal -->
        <div class="modal fade" ref="formModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false" data-bs-focus="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header py-2 bg-secondary text-light">
                        <h5 class="modal-title" style="font-weight: bold">{{ form.id > 0 ? "Edit" : "Add New" }}
                            Customer</h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="isLoading" class="py-2 px-4 text-secondary">
                            <div class="spinner"></div> Loading
                        </div>
                        <div v-else>
                            <form @submit.prevent="saveData" id="form"></form>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-12 mb-2 required">
                                            <label class="form-label">Name</label>
                                            <input type="text"
                                                :class="['form-control', { 'is-invalid': form.errors.name }]"
                                                v-model="form.name" ref="autofocus" :disabled="form.processing" />
                                            <span v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name
                                                }}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-2 required">
                                            <label class="form-label">Gender</label>
                                            <select v-model="form.gender" :disabled="form.processing"
                                                :class="['form-select', { 'is-invalid': form.errors.gender }]">
                                                <option v-for="option in dd_gender" :value="option.id">
                                                    {{ option.label }}
                                                </option>
                                            </select>
                                            <span v-if="form.errors.gender" class="invalid-feedback">{{
                                                form.errors.gender }}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-2 required">
                                            <label class="form-label">Email</label>
                                            <input type="text"
                                                :class="['form-control', { 'is-invalid': form.errors.email }]"
                                                v-model="form.email" :disabled="form.processing" />
                                            <span v-if="form.errors.email" class="invalid-feedback"> {{
                                                form.errors.email }} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label class="form-label">Photo</label>
                                            <div class="text-center" style="position: relative;">
                                                <i class="bi bi-x-circle fs-3 m-0 p-0 text-danger"
                                                    style="position: absolute; right: 20px;top: -20px; cursor: pointer;"
                                                    @click.stop="removeImage"></i>
                                                <img :src="form.image_preview"
                                                    style="width: 180px; height: 180px;cursor: pointer;"
                                                    class="img-thumbnail" @click="upload" />
                                                <span v-if="form.errors.image" class="invalid-feedback"> {{
                                                    form.errors.image }} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pt-1" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-4" :disabled="form.processing" form="form">
                            <i v-if="form.processing" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></i>
                            <i v-else class="bi bi-floppy" style="padding-right: 3px;"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="pagetitle">
                <a type="button" class="btn btn-primary" style="float: right" @click="openModal">
                    <i class="bi bi-plus-circle"></i> Add New
                </a>
                <h2>Customers List</h2>
                <hr />
            </div>

            <form @submit.prevent="searchData">
                <div class="row pb-3">
                    <div class="col-md-10">
                        <div class="row justify-content-start">
                            <div class="col-lg-3 col-sm-6">
                                <label class="form-label mb-0">Name</label>
                                <input type="text" class="form-control" v-model="filterForm.search"
                                    placeholder="Search..." />
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <label class="form-label mb-0">Gender</label>
                                <select class="form-select" v-model="filterForm.gender">
                                    <option value="0">ALL</option>
                                    <option v-for="option in dd_gender" :value="option.id">
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-secondary pt-1" style="float: right">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="60px" class="text-center">No</th>
                        <th width="150px" class="text-center">Photo</th>
                        <th @click="sortData('name')" style="cursor: pointer">
                            Name <i class="text-secondary"
                                :class="filterForm.field == 'name' ? (filterForm.order == 'desc' ? 'bi bi-sort-alpha-down-alt' : 'bi bi-sort-alpha-down') : 'bi bi-arrow-down-up'"></i>
                        </th>
                        <th @click="sortData('gender')" style="cursor: pointer;">
                            Gender <i class="text-secondary"
                                :class="filterForm.field == 'gender' ? (filterForm.order == 'desc' ? 'bi bi-sort-alpha-down-alt' : 'bi bi-sort-alpha-down') : 'bi bi-arrow-down-up'"></i>
                        </th>
                        <th @click="sortData('email')" style="cursor: pointer; padding-right: 50px;">
                            Email <i class="text-secondary"
                                :class="filterForm.field == 'email' ? (filterForm.order == 'desc' ? 'bi bi-sort-alpha-down-alt' : 'bi bi-sort-alpha-down') : 'bi bi-arrow-down-up'"></i>
                        </th>
                        <th @click="sortData('created_at')" style="cursor: pointer">
                            Created Date <i class="text-secondary"
                                :class="filterForm.field == 'created_at' ? (filterForm.order == 'desc' ? 'bi bi-sort-alpha-down-alt' : 'bi bi-sort-alpha-down') : 'bi bi-arrow-down-up'"></i>
                        </th>
                        <th width="150px" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="page.props.data && page.props.data.data && page.props.data.data.length > 0"
                        v-for="(d, index) in page.props.data.data" :key="d.id">
                        <th style="vertical-align: middle;text-align: center">{{ page.props.data.from + index }}</th>
                        <td class="text-center py-2"><img style="width: 60px;height: 60px;"
                                :src="d.image ? ('storage/' + d.image) : defaultImage" />
                        </td>
                        <td style="vertical-align: middle">{{ d.name }}</td>
                        <td style="vertical-align: middle">{{ d.gender == 1 ? 'Female' : 'Male' }}</td>
                        <td style="vertical-align: middle">{{ d.email }}</td>
                        <td style="vertical-align: middle">{{ dayjs(d.created_at).format("DD/MM/YYYY HH:mm:ss") }} </td>
                        <td style="vertical-align: middle; text-align: center;">
                            <i class="bi bi-trash delete-icon mx-2 text-danger fs-5" style="cursor: pointer;"
                                @click.stop="deleteData(d.id)"></i>
                            <i class="bi bi-pencil-square edit-icon mx-2 text-success fs-5" style="cursor: pointer;"
                                @click.stop="editData(d.id)"></i>
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="10" class="shadow-none">
                            No record found
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="row pt-3 float-end">
                <!-- Pagination -->
                <nav v-if="page.props.data && page.props.data.links && page.props.data.links.length > 3">
                    <ul class="pagination">
                        <li v-for="(link, index) in page.props.data.links" :key="index" class="page-item"
                            :class="{ 'active': link.active, 'disabled': !link.url }">
                            <Link class="page-link" :href="link.url ?? '#'" v-html="link.label">
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Modal } from "bootstrap";
import { onMounted, onUnmounted, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";

const isLoading = ref(false);
const page = usePage();
const dd_gender = ref([
    { id: 1, label: "Female" },
    { id: 2, label: "Male" }
]);
const defaultImage = "default.png";
const form = useForm({
    id: 0,
    name: null,
    gender: null,
    email: null,
    image: null,
    image_preview: defaultImage,
    is_deleted_image: null
});
const queryParams = new URLSearchParams(window.location.search);
const filterForm = useForm(
    {
        name: queryParams.get('search'),
        gender: queryParams.get('gender'),
        field: queryParams.get('field'),
        order: queryParams.get('order')
    }
);
const formModal = ref(null);
const formModalInstance = ref(null);
const deleteModal = ref(null);
const deleteModalInstance = ref(null);
const successModal = ref(null);
const successModalInstance = ref(null);

const image_error = ref(null);

const deleteURL = ref('');

const autofocus = ref(null);

const removeImage = () => {
    form.is_deleted_image = 1;
    form.image = null;
    form.image_preview = defaultImage;
    image_error.value = null;
};

const upload = () => {
    let acceptFileType = ['image/png', 'image/jpg', 'image/jpeg'];
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = '.png,.jpg,.jpeg';
    input.onchange = _ => {
        let file = input.files[0];
        if (!acceptFileType.includes(file.type.toLocaleLowerCase())) {
            image_error.valueOf = 'Accept file type: png, jpg, jpeg';
            return;
        } else if (file.size > 2097152) {
            image_error.value = 'File size must be less than 2mb';
            return;
        }
        form.image_preview = URL.createObjectURL(file);
        form.image = file;
    };
    input.click();
};

onMounted(() => {
    if (formModal.value) {
        formModalInstance.value = new Modal(formModal.value);
        formModal.value.addEventListener("shown.bs.modal", () => {
            autofocus.value.focus();
            autofocus.value.select();
        });
        formModal.value.addEventListener("hide.bs.modal", () => {
            document.activeElement?.blur();
            form.reset();
            form.clearErrors();
        });
    }
    if (deleteModal.value) {
        deleteModalInstance.value = new Modal(deleteModal.value);
        deleteModal.value.addEventListener("hide.bs.modal", () => {
            document.activeElement?.blur();
        });
    }
    if (successModal.value) {
        successModalInstance.value = new Modal(successModal.value);
        successModal.value.addEventListener("hide.bs.modal", () => {
            document.activeElement?.blur();
        });
    }
});

onUnmounted(() => {
    if (formModalInstance.value) {
        formModalInstance.value.dispose();
    }
    if (deleteModalInstance.value) {
        deleteModalInstance.value.dispose();
    }
    if (successModalInstance.value) {
        successModalInstance.value.dispose();
    }
});


// add or create
const openModal = () => {
    isLoading.value = false;
    form.image_preview = defaultImage;
    form.image = null;
    form.is_deleted_image = null;
    formModalInstance.value.show();
};


// submit form
const saveData = () => {
    form.post("/submit", {
        _method: form.id > 0 ? "put" : "post",
        preserveState: true,
        replace: true,
        preserveScroll: true,
        onSuccess: () => {
            formModalInstance.value.hide();
            successModalInstance.value.show();
        },
        onError: () => autofocus.value.focus()
    });
};


// search
const searchData = () => {
    filterForm.get('/', { preserveState: true, replace: true, preserveScroll: true });
};

// sort
const sortData = (field) => {
    if (filterForm.field === field) {
        filterForm.order = filterForm.order == 'asc' ? 'desc' : 'asc';
    } else {
        filterForm.field = field;
        filterForm.order = 'asc';
    }
    searchData();
};

// edit
const editData = async (id) => {
    openModal();
    isLoading.value = true;
    const response = await fetch("/edit/" + id);
    const data = await response.json();
    isLoading.value = false;
    form.id = data.id;
    form.name = data.name;
    form.gender = data.gender;
    form.email = data.email;
    form.image_preview = (data.image ? 'storage/' + data.image : defaultImage);
};

// delete
const deleteData = (id) => {
    deleteURL.value = "/delete/" + id;
    deleteModalInstance.value.show();
};

</script>