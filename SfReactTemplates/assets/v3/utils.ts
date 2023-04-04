import { NaeSProperties } from "../_custom/config/NaeSProperties";
import { NaeSSchema } from "../_custom/config/NaeSSchema";
import { NaeSStatuses } from "../_custom/config/NaeSStatuses";


export const getTabFieldsToReturn = (tab: INaeTab | null) => {
  let fieldsToReturn: string[] = ["scopes", "_viewTitle"];
  if (!!tab && tab.fields) {
    tab.fields.forEach((f: INaeTabField) => {
      fieldsToReturn.push(f.relName ? f.key + "." + f.relName : f.key);
      if (f.custom && f.custom.fieldsToReturn) {
        fieldsToReturn = [...fieldsToReturn, ...f.custom.fieldsToReturn];
      }
    });
  } else {
    fieldsToReturn.push("id");
  }
  return fieldsToReturn;
};

export const getSchemaTitle = (_schema: string, plural: boolean) => {
  const schemas = NaeSSchema;

  let title: string = _schema
  schemas.forEach((s: INaeSchema) => {
    if (s.schema === _schema) {
      if (plural) {
        title = s.titlePlural
      } else {
        title = s.title
      }
    }
  })
  return title
}

export const getPropertyTitleForSchema = (_schema: string, key: string) => {
  const data = getPropertyDataForSchema(_schema, key)
  return data.title
}
export const getPropertyDataForSchema = (
  _schema: string,
  key: string
): INaeProperty => {
  const properties = NaeSProperties
  const filterProperties = properties.filter((p: INaeProperty) => {
    return p.schema === _schema && p.key === key
  })
  if (filterProperties.length > 0) {
    return filterProperties[0]
  }
  return getDefProperty(key, _schema)
}
export const getDefProperty = (key: string, schema: string) => {
  return {
    key: key,
    type: 'NA',
    title: '',
    schema: schema,
    className: schema
  }
}

export const getPropertyForPath = (_path: string) => {
  const path = _path.split('.')

  if (path.length === 1) {
    return null
  } else {
    let _schema = ''
    for (let i = 0; i < path.length; i++) {
      if (i === 0) {
        _schema = path[0]
      } else if (i === path.length - 1) {
        const prop = getPropertyDataForSchema(_schema, path[i])
        if (!!prop && prop.type !== 'NA') {
          return prop
        } else {
          // console.log('no prop', _schema, path[i])
          return null
        }
      } else {
        const _lastSchema = _schema
        _schema = ''
        const prop = getPropertyDataForSchema(_lastSchema, path[i])
        if (!!prop && prop.type !== 'NA') {
          if ((prop.type === 'array' || prop.type === 'rel') && !!prop.format) {
            _schema = prop.format
          }
        } else {
          // console.log('no prop rel', _schema, path[i])
        }
      }
    }
  }
  return null
}

export const getPropertyEnumLabel = (
  schema: string,
  key: string,
  val: number | string
) => {
  const field = getPropertyDataForSchema(schema, key)

  if (!field) {
    return '-1'
  }
  if (!field.enum) {
    return '-2'
  }
  const filtered = field.enum.filter((element: any) => element.value === val)
  if (filtered.length > 0) {
    return filtered[0].label
  }

  return '-3'
}

export const getStatusForSchemaAndType = (
  schema: string,
  type: string,
  status: number
): INaeStatus => {
  const activeStatus = NaeSStatuses.filter(
    (s) => s.status === status && s.type === type && s.schema === schema
  )
  if (activeStatus.length > 0) {
    return activeStatus[0]
  }
  return {
    schema: schema,
    type: type,
    status: status,
    text: 'Nerastas',
    bgColor: 'blue',
    brightness: 500
  }
}

export interface INaeEditFieldTagCloud {
  action: string
  field: string
}

export interface INaeEditField {
  key?: string
  type?: string
  text?: string
  relKey?: string
  relKeyExtraSelect?: string[]
  colSize?: string
  hideLabel?: boolean
  relList?: string
  relListObj?: INaeTab
  relListObjFunc?: () => INaeTab
  extraFilter?: (element: any, key: string) => any
  extraCreateStateOptions?: (element: any, key: string) => any
  fieldDependency?: string,
  custom?: INaeEditFieldCustom
  tagCloud?: INaeEditFieldTagCloud
  disableCreateElement?: boolean,
  inputClassName?: string,
  labelClassName?: string,
}
export interface INaeFormViewRow extends Array<INaeViewField> {}
export interface INaeFormEditRow extends Array<INaeEditField> {}
export interface INaeViewSettings {
  fields: INaeFormViewRow[]
  schema: string
  horizontal?: boolean
  type: string
}

export interface INaeViewFieldCustomRenderProps {
  field: INaeViewField
  element: any
  property: INaeProperty | null
  schema?: string
}
export interface INaeViewFieldCustom {
  fieldsToReturn?: string[]
  customRender?: (props: INaeViewFieldCustomRenderProps) => any
}
export interface INaeViewField {
  key?: string
  type?: string
  text?: string
  relKey?: string
  colSize?: string
  hideLabel?: boolean
  relList?: string
  relListObj?: INaeTab
  relListObjFunc?: () => INaeTab
  relKeyExtraSelect?: string[]
  custom?: INaeViewFieldCustom
  showDescription?: boolean
}


export interface INaeEditFieldCustomRenderProps {
  field: INaeViewField
  element: any
  property: INaeProperty | null
  schema?: string
  updateElement: (key: string, val: any) => void
}
export interface INaeEditFieldCustom {
  fieldsToReturn?: string[]
  customRender?: (props: INaeEditFieldCustomRenderProps) => any
  renderAfterField?: (props: INaeEditFieldCustomRenderProps) => any
}
export interface INaeFormViewRow extends Array<INaeViewField> {}
export const getElementFieldsToReturn = (
  viewFields: INaeViewSettings | INaeEditSettings,
  getDepenciesForField: (key: string) => string[]
) => {
  let fieldsToReturn: string[] = [
    'id',
    'creator.fullName',
    'doer.fullName',
    'createdAt',
    'updatedAt',
    'scopes',
    'pdfFileName',
    '_viewTitle',
    'serialNumber'
  ]
  // @ts-ignore
  viewFields.fields.forEach((row: INaeFormViewRow) => {
    row.forEach((f) => {
      if (f.relListObjFunc) {
        f.relListObj = f.relListObjFunc();
      }
      if (f.key && f.relListObj) {
        let relKey = f.key + ':'
        let relKeys: string[] = ['id', 'scopes']
        f.relListObj.fields.forEach((tabField) => {
          if (tabField.relName) {
            relKeys.push(tabField.key + '.' + tabField.relName)
          } else {
            relKeys.push(tabField.key)
          }
        })

        relKey += relKeys.join(',')
        fieldsToReturn.push(relKey)
      } else if (f.key && f.relKey) {
        fieldsToReturn.push(f.key + '.' + f.relKey)
      } else if (f.key) {
        fieldsToReturn.push(f.key)
      }
      if (f.custom && f.custom.fieldsToReturn) {
        fieldsToReturn = [...fieldsToReturn, ...f.custom.fieldsToReturn]
      }
      if (f.relKeyExtraSelect) {
        f.relKeyExtraSelect.forEach(ff => {
          fieldsToReturn.push(ff);
        })
      }
      if (f.key) {
        const deps = getDepenciesForField(f.key)
        if (deps && deps.length > 0) {
          fieldsToReturn = [...fieldsToReturn, ...deps]
        }
      }
    })
  })
  return fieldsToReturn
}
export interface INaeEditSettings {
  fields: INaeFormEditRow[]
  schema: string
  horizontal?: boolean
  type: string
}

export const getSchemaClassNameBySchema = (schema: string) => {
  const schemas = NaeSSchema

  const _schemaA = schemas.filter((s) => s.schema === schema)
  if (_schemaA.length > 0) {
    return _schemaA[0].className
  }
  return '-'
}

export interface ITableLocationStateDates {
  dateFrom: string
  dateTo: string
}


export const getStatusForProperty = (
  property: INaeProperty,
  status: number
): INaeStatus => {
  const activeStatus = NaeSStatuses.filter(
    (s) =>
      s.status === status &&
      s.type === property.key &&
      s.schema === property.schema
  )
  if (activeStatus.length > 0) {
    return activeStatus[0]
  }
  return {
    schema: property.schema,
    type: property.key,
    status: status,
    text: 'Nerastas',
    bgColor: 'blue',
    brightness: 500
  }
}

export interface INaeStatus {
  schema: string
  type: string
  status: number
  text: string
  bgColor: string
  brightness: number
  variant?: string
}

export interface INaeTab {
  schema: string
  fields: INaeTabField[]
  type: string
  // links: string[];
  sort: INaeTabSort[]

  predefinedFilter?: any
  quickSearchFilterKeys?: (INaeTabQuickSearch | string)[]
  title?: string
  filterDateKey?: string
  showPivot?: boolean

  exports?: INaeListExport[]

  disableCreate?: boolean

  tabGroup?: string
  tabGroupTitle?: string

  totals?: INaeTotal[],

  pageSize?: number,
  pageExport?: boolean,
}
export interface INaeTabSort {
  key: string
  value: string
}
export interface INaeTabQuickSearch {
  key: string
  directSelect: boolean
  method?: string
}

export interface INaeListExportField {
  key: string
  relName?: string
  allowEdit?: boolean
}

export interface INaeListExport {
  schema: string
  title: string
  type: string
  fields: INaeListExportField[]
}
export interface INaeTotal {
  path: string
  field: string
  title: string
  type: string
}

export interface INaeSchema {
  className: string
  schema: string
  title: string
  titlePlural: string
  required?: string[]
  scopes?: string[]
}

export interface INaePropertyEnum {
  value: string | number
  label: string
}

export interface INaeProperty {
  key: string
  type: string
  typeDesc?: string
  title: string
  format?: string
  description?: string
  additionalProperties?: any // TODO
  schema: string
  className: string
  as?: string
  enum?: INaePropertyEnum[]
  isDb?: boolean
  dbType?: string
  naeType?: string
}

export interface INaePdf {
  schema: string
  template: string
  title: string
  skipList?: boolean
  sort: number,
  skipWithoutSign?: boolean
}

export const getSchemaByClassName = (className: string) => {
  const schemas = NaeSSchema;

  const _schemaA = schemas.filter((s) => s.className === className)
  if (_schemaA.length > 0) {
    return _schemaA[0].schema
  }
  return '-'
}
export interface TransformViewValueOptions {
  compactView?: boolean;
  contentClassName?: string;
}
export interface INaeTabFieldCustomProps {
  column: TheadCol
  property: INaeProperty
  item: any
  tabField: INaeTabField
  navigate: (schema: string, id: number | string, item: any) => void
}

export interface INaeTabFieldCustom {
  fieldsToReturn?: string[]
  thead?: TheadCol
  tbody?: (obj: INaeTabFieldCustomProps) => TrowCol
}

export interface INaeTabField {
  key: string
  titlePath?: string
  sortPath?: string
  filterPath?: string
  relName?: string
  sortCol?: string
  link?: boolean
  linkNl?: boolean
  disableSort?: boolean
  custom?: INaeTabFieldCustom
  editable?: boolean
  onEditCallback?: (el: any) => void,
}
export interface INaeTabSort {
  key: string
  value: string
}
export interface TheadCol {
  props: TableThProps
  content: any
  keyPath?: string
  sortPath?: string
  filterPath?: string
}
export interface TableThProps
  extends React.DetailedHTMLProps<
    React.ThHTMLAttributes<HTMLTableHeaderCellElement>,
    HTMLTableHeaderCellElement
  > {
  size?: TableSize | string
}
export interface TrowCol {
  props: TableTdProps
  content: any
}
export interface TableTdProps
  extends React.DetailedHTMLProps<
  React.TdHTMLAttributes<HTMLTableDataCellElement>,
  HTMLTableDataCellElement
  > {
  size?: TableSize | string
}
export enum TableSize {
  sm = 'sm',
  base = 'base',
  lg = 'lg'
}


export const defTableSort = {
    key: 'i.id',
    value: 'ASC',
  }