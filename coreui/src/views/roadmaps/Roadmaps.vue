<template>
  <CRow>
    <CCol col="12" xl="12">
<!--      <input type="text" v-model="keywords">-->
      <transition name="slide">
        <CCard>
          <CCardBody>
            <CButton color="primary" @click="createRoadmap()">Create Roadmap</CButton>
            <CAlert
                :show.sync="dismissCountDown"
                color="primary"
                fade
            >
<!--              ({{dismissCountDown}}) -->
              {{ message }}
            </CAlert>
            <CDataTable
                hover
                :items="items"
                :fields="fields"
                :items-per-page="10"
                pagination

                table-filter
                sorter
            >
              <template #user_name="{item}">
                <td>
                  <strong>{{item.user_name}}</strong>
                </td>
              </template>
              <template #author="{item}">
                <td>
                  <strong>{{item.client_company_name}}</strong>
                </td>
              </template>
              <template #title="{item}">
                <td>
                  <strong>{{item.client_client_name}}</strong>
                </td>
              </template>
              <template #author="{item}">
                <td>
                  <strong>{{item.material_name}}</strong>
                </td>
              </template>
              <template #region_name="{item}">
                <td>
                  <strong>{{item.description}}</strong>
                </td>
              </template>
              <template #author="{item}">
                <td>
                  <strong>{{item.applies_to_date}}</strong>
                </td>
              </template>
              <template #show="{item}">
                <td>
                  <CButton color="primary" @click="showRoadmap( item.id )">Show</CButton>
                </td>
              </template>
              <template #edit="{item}">
                <td>
                  <CButton color="primary" @click="editRoadmap( item.id )">Edit</CButton>
                </td>
              </template>
              <template #delete="{item}">
                <td>
                  <CButton v-if="item.id" color="danger" @click="deleteRoadmap( item.id )">Delete</CButton>
                </td>
              </template>
            </CDataTable>
          </CCardBody>
        </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Clients',
  data: () => {
    return {
      items: [],
      /*
      fields: [
        {key: 'author'},
        {key: 'title'},
        {key: 'content'},
        {key: 'applies_to_date'},
        {key: 'status'},
        {key: 'note_type'},
        {key: 'show'},
        {key: 'edit'},
        {key: 'delete'}
      ],
      */
      fields: ['id','user_name','client_company_name','client_client_name','material_name','description','applies_to_date','show','edit','delete'],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 2,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  computed: {
  },
  methods: {
    getRowCount (items) {
      return items.length
    },
    roadmapLink (id) {
      return `roadmaps/${id.toString()}`
    },
    editLink (id) {
      return `roadmaps/${id.toString()}/edit`
    },
    showRoadmap ( id ) {
      const roadmapLink = this.roadmapLink( id );
      this.$router.push({path: roadmapLink});
    },
    editRoadmap ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteRoadmap( id ) {
      let self = this;
      let clientId = id;
      axios.post(  this.$apiAdress + '/api/roadmaps/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
          .then(function (response) {
            self.message = 'Successfully deleted Roadmap.';
            self.showAlert();
            self.getRoadmaps();
          }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    createRoadmap () {
      this.$router.push({path: 'roadmaps/create'});
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getRoadmaps (){
      let self = this;
      axios.get(this.$apiAdress + '/api/roadmaps?token=' + localStorage.getItem("api_token"))
          .then(function (response) {
            self.items = response.data;
          }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getRoadmaps();
  }
}

</script>
<style scoped>
.card-body >>> table > tbody > tr > td {
  cursor: pointer;
}
</style>
