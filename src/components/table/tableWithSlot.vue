<template>
  <div class="table-with-slot">
    <div class="table-column-container">
      <slot></slot>
    </div>

    <div v-if="!tableData.length" class="table-no-data">No Data</div>
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
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

.table-with-slot {

  .table-column-container{
    // box-shadow: 0 0 3px @main-color;
    display: flex;
    flex-direction: row;
  }

  .table-no-data {
    height: 40px;
    color: @main-color;
    font-weight: bold;
    line-height: 40px;
    text-align: center;
    box-shadow: 0 0 3px @main-color;
  }
}
</style>
