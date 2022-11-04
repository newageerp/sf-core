import React, { useState, useEffect } from 'react'
import { FieldSelect } from '@newageerp/v3.form.field-select'
import { getTabFromSchemaAndType } from '../utils'
import { OpenApi } from '@newageerp/nae-react-auth-wrapper'

interface Props {
  className?: string
  components?: any

  value?: any
  onChange: (val: any) => void
  schema: string

  tab?: any
  fieldKey?: string
  fieldKeyExtraSelect?: string[]

  extraFilter?: any

  addEmpty?: boolean
}

export default function SelectFieldSchema(props: Props) {
  const { tab } = props

  const [options, setOptions] = useState<any[]>([])

  let _tab = tab ? tab : getTabFromSchemaAndType(props.schema, 'main')

  const fieldKey = props.fieldKey ? props.fieldKey : _tab.fields[0].key

  let fieldsToReturn: string[] = [
    'id',
    fieldKey,
    ...(props.fieldKeyExtraSelect ? props.fieldKeyExtraSelect : [])
  ]

  const [schemaGetData, schemaGetDataParams] = OpenApi.useUList(
    props.schema,
    fieldsToReturn
  )

  useEffect(() => {
    schemaGetData(
      props.extraFilter ? props.extraFilter : [],
      1,
      9999,
      _tab.sort
    )
  }, [])

  useEffect(() => {
    if (fieldKey) {
      if (props.addEmpty) {
        setOptions([
          { value: 0, label: '' },
          ...schemaGetDataParams.data.data.map((d: any) => {
            return {
              value: d.id,
              label: d[fieldKey]
            }
          })
        ])
      } else {
        setOptions(
          schemaGetDataParams.data.data.map((d: any) => {
            return {
              value: d.id,
              label: d[fieldKey]
            }
          })
        )
      }
    }
  }, [schemaGetDataParams.data.data, tab])

  const findElement = (id: number) => {
    const f = schemaGetDataParams.data.data.filter((e: any) => e.id === id)
    if (f.length > 0) {
      return f[0]
    }
    return { id }
  }

  return (
    <FieldSelect
    //   required={props.required}
      className={props.className}
      components={props.components}
      options={options}
      value={props.value ? props.value.id : 0}
      onChange={(v) => props.onChange(findElement(v))}
      icon='bars'
    />
  )
}
