<template>
  <div class="table-with-slot">
    <div class="table-title-container">
      <!-- <slot></slot> -->
    </div>

    <div class="table-content-container">
      <div
        class="table-row"
        v-for="(row, index) in tableData"
        :key="row + index">
        <slot :row="row"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TableWithSlot',
  props: ['tableData'],
  data () {
    return {
    }
  },
  methods: {

  },
  mounted () {
    console.error(this.$children)
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

.table-with-slot {
  width: 100%;
  box-shadow: 0 0 3px @main-color;

  .table-title-container {
    display: flex;
    flex-direction: row;
  }

  .table-content-container {
    .table-row {
      display: flex;
      height: 40px;
      box-shadow: 0 0 3px @main-color;
    }
  }
}
</style>
