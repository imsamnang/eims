<style>
    .table-xs td,
    .table-xs th {
        border: 1px solid #ccc;
        padding: .3rem .07rem !important;
        font-size: .8rem !important;
        text-align: center;
    }

    .table-xs td,
    .table-xs th {
        vertical-align: middle;

    }
</style>
<div class="card">
    <div class="card-header p-2 px-3">
        <label class="label-arrow label-primary label-arrow-right">
            (A) គំរូ
        </label>
        <a class="float-right" href="{{str_replace("/add","excel/template",config("pages.form.action.add"))}}">
            ទាញយកគំរូ
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-xs table-bordered ">
                <thead>
                    <th>ល.រ</th>
                    <th>ឈ្មោះពេញ (ជាភាសាខ្មែរ)</th>
                    <th>ឈ្មោះពេញ (ជាភាសាឡាតាំង)</th>
                    <th>ភេទ</th>
                    <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                    <th>ស្ថានភាពគ្រួសារ</th>
                    <th>អាស័យ​ដ្ឋាន​អ​ចិ​ន្រ្តៃ​យ៍</th>
                    <th>អាស័យដ្ឋានបណ្តោះអាសន្ន</th>
                    <th><i class="fas text-red fa-key"></i> លេខទូរស័ព្ទ</th>
                    <th><i class="fas text-red fa-key"></i> អ៊ីម៉ែល</th>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>សែម គឹមសាន</td>
                        <td>Sem Keamsan</td>
                        <td>
                            <select class="form-control">
                                @foreach ($gender as $o)
                                <option value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>16/10/1998</td>
                        <td>
                            <select class="form-control">
                                @foreach ($marital as $o)
                                <option value="{{$o}}">{{$o}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>ត្រពាំងទឹម កណ្តែក ប្រាសាទបាគង ខេត្តសៀមរាប</td>
                        <td>ត្រពាំងទឹម កណ្តែក ប្រាសាទបាគង ខេត្តសៀមរាប</td>
                        <td>0969140554</td>
                        <td>keamsan.sem@gmail.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
