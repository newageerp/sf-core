import React, { useState, Fragment } from 'react'
import { useTranslation } from 'react-i18next'
// import Td from './OldTd'
// import Th from './OldTh'
import { defaultStrippedRowClassName, TrowCol } from './OldTrow'
import { getDefProperty, getPropertyDataForSchema, getPropertyForPath, getTabFieldsToReturn, getTextAlignForProperty, INaeTab } from '../utils'
import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import { editPopupBySchemaAndType } from '../edit/EditPopup'
import OldTable, { TheadCol } from './OldTable'
import OldThead from './OldThead'
import OldTbody from './OldTbody'
import moment from 'moment'
import OldBadge, { BadgeSize } from './OldBadge'
import { NaeSStatuses } from '../../_custom/config/NaeSStatuses'
import { nl2p } from '@newageerp/v3.bundles.utils-bundle'
import OldTabSelectField from './OldTabSelectField'
import OldTabFloatField from './OldTabFloatField'
import OldTabTextareaField from './OldTabTextareaField'
import OldTabStringField from './OldTabStringField'
import { MainButton, RsButton, ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import { Table, Td, Th } from '@newageerp/ui.ui-bundle'
import { Template, TemplatesLoader } from '@newageerp/v3.templates.templates-core'

interface Props {
  schema: string
  title: string

  value: any[]
  onChange: (val: any[]) => void

  tab: INaeTab
  parentElement?: any

  disableCreateElement?: boolean

  toolbar: Template[],
}

export default function OldArrayFieldComponent(props: Props) {
  const [editPopupId, setEditPopupId] = useState<number>(0)
  const [copyPopupId, setCopyPopupId] = useState<number>(0)

  const fieldsToReturn: string[] = getTabFieldsToReturn(props.tab)

  const [schemaGetData] = OpenApi.useUList(props.schema, fieldsToReturn)

  const localTab: INaeTab = JSON.parse(JSON.stringify(props.tab));

  localTab.fields.map(f => {
    f.onEditCallback = (el) => {
      replaceElement(el);
    }
    return f;
  })

  const addElement = (el: any, back: any) => {
    schemaGetData([{ and: [['i.id', 'eq', el.id]] }], 1, 1).then((res: any) => {
      const newEl = [...props.value, res.data.data[0]]
      props.onChange(newEl)
      setCopyPopupId(0)
    })
  }

  const replaceElement = (el: any) => {
    schemaGetData([{ and: [['i.id', 'eq', el.id]] }], 1, 1).then((res: any) => {
      const newEl = props.value.map((_el: any) => {
        if (el.id === _el.id) {
          return res.data.data[0]
        }
        return _el
      })
      props.onChange(newEl)
      setEditPopupId(0)
    })
  }

  const { t } = useTranslation()

  const removeElement = (index: number) => {
    const newEl = props.value.filter((_el: any, _i: number) => _i !== index)
    props.onChange(newEl)
  }

  const bodyProps = {
    data: props.value,
    callback: (item: any, index: number) => {
      const scopes = item.scopes ? item.scopes : [];
      let rowClassName = "";

      scopes.forEach((scope: string) => {
        if (scope.indexOf('bg-row-color:') > -1) {
          const scopeA = scope.split(":");
          rowClassName = scopeA[1];
        }
      })

      const extraContentEnd = (
        <Fragment>
          <Td className={'tw3-flex tw3-gap-2 tw3-justify-end'}>
            <ToolbarButton
              iconName='edit'
              onClick={() => {
                setEditPopupId(item.id)
              }}
            />
            <ToolbarButton
              iconName='copy'
              onClick={() => {
                setCopyPopupId(item.id)
              }}
            />
            <ToolbarButton
              iconName='trash'
              onClick={() => removeElement(index)}
              textColor={'tw3-text-red-500'}
              confirmation={true}
            />
          </Td>
        </Fragment>
      )

      return {
        columns: tdBody(
          item,
          localTab,
          props.schema,
          (_schema, id) => {
            setEditPopupId(
              typeof id === 'string' ? parseInt(id, 10) : id
            )
          }
        ),
        className: rowClassName,
        extraContentEnd: extraContentEnd
      }
    }
  }

  return (
    <Fragment>
      <div className='tw3-rounded tw3-border tw3-border-slate-300 tw3-p-2 tw3-bg-white'>
        <div className={'tw3-space-y-4'}>
          {/* <MainButton iconName='plus' onClick={toggleCreateNew}>
            {t('Add')}
          </MainButton> */}
          <TemplatesLoader
            templates={props.toolbar}
            templateData={{
              addElement,
              parentElement: props.parentElement
            }}
          />
          <OldTable
            containerClassName={'tw3-w-full'}
            thead={
              <OldThead
                columns={getThColums({ tab: localTab, schema: props.schema })}
                extraContentEnd={<Th textAlignment={'tw3-text-right'}>{t('Actions')}</Th>}
              />
            }
            tbody={<OldTbody {...bodyProps} />}
          />

        </div>
      </div>

      {editPopupId > 0 &&
        <Fragment>
          {editPopupBySchemaAndType(props.schema, 'main', {
            onClose: () => setEditPopupId(0),
            editProps: {
              schema: props.schema,
              id: editPopupId.toString(),
              onSaveCallback: replaceElement,
              parentElement: props.parentElement,
              type: 'main'
            },
          })}
        </Fragment>
      }

      {copyPopupId > 0 &&
        <Fragment>
          {editPopupBySchemaAndType(props.schema, 'main', {
            onClose: () => setCopyPopupId(0),
            editProps: {
              schema: props.schema,
              id: "new",
              onSaveCallback: addElement,
              parentElement: props.parentElement,
              type: 'main',
              newStateOptions: {
                createOptions: {
                  convert: {
                    schema: 'cargo',
                    element: { id: copyPopupId }
                  }
                }
              }
            },
          })}
        </Fragment>
      }

    </Fragment>
  )
}

export interface ThColumnsProps {
  tab: INaeTab
  schema: string
  ignoreColumns?: string[]
}

export const getThColums = (props: ThColumnsProps): TheadCol[] => {
  const { tab, schema, ignoreColumns } = props

  let fields = tab.fields
  if (!!ignoreColumns) {
    fields = fields.filter((f) => ignoreColumns.indexOf(f.key) === -1)
  }

  const thColumns = fields.map((f) => {
    const _key = f.titlePath ? f.titlePath : f.key
    const isKeyPath = f.key.indexOf('.') > -1

    const keyPath = isKeyPath
      ? f.key
      : f.relName
        ? `${schema}.${f.key}.${f.relName}`
        : `${schema}.${f.key}`

    let property = isKeyPath
      ? getPropertyForPath(schema + '.' + _key)
      : getPropertyDataForSchema(schema, _key)

    if (!property) {
      property = getDefProperty(_key, schema)
    }

    if (f.custom && f.custom?.thead) {
      return f.custom.thead
    } else {
      return transformThProps(
        {
          props: {},
          content: property.title,
          keyPath: keyPath,
          sortPath: f.sortPath ? f.sortPath : keyPath,
          filterPath: f.filterPath ? f.filterPath : keyPath
        },
        property,
        f.link
      )
    }
  })
  return thColumns
}


export const tdBody = (
  item: any,
  tab: INaeTab,
  schema: string,
  navigate: (schema: string, id: number | string, item: any) => void,
  ignoreColumns?: string[]
): TrowCol[] => {
  let fields = tab.fields
  if (!!ignoreColumns) {
    fields = fields.filter((f) => ignoreColumns.indexOf(f.key) === -1)
  }

  return fields.map((f) => {
    const isKeyPath = f.key.indexOf('.') > -1
    const keyPath = schema + '.' + f.key;

    let property = isKeyPath
      ? getPropertyForPath(keyPath)
      : getPropertyDataForSchema(schema, f.key)

    if (!property) {
      property = getDefProperty(f.key, schema)
    }

    let _item = item
    if (isKeyPath) {
      const path = keyPath.split('.')

      if (path.length > 2) {
        for (let i = 1; i < path.length - 1; i++) {
          _item = _item[path[i]]
        }
      }
    }
    if (!_item) {
      _item = {};
    }

    if (f.custom && f.custom.tbody) {
      return f.custom.tbody({
        column: {
          props: {},
          content: ''
        },
        property,
        item: _item,
        tabField: f,
        navigate
      })
    } else {
      return transformTdProps({
        column: {
          props: {},
          content: ''
        },
        property,
        item: _item,
        tabField: f,
        navigate
      })
    }
  })
}


export const transformTdProps = (obj: any) => {
  const { column, property, tabField, navigate } = obj
  const item = obj.item ? obj.item : {}
  const textAlignClassName = getTextAlignForProperty(property)
  column.props.className += ' ' + textAlignClassName

  const isStringArray =
    property.type === 'array' && property.format === 'string'

  const isFloat = property.type === 'number' && property.format === 'float'
  const isNumber =
    (property.type === 'number' && property.format === 'float') ||
    (property.type === 'integer' && !property.enum)
  const isBoolean = property.type === 'bool' || property.type === 'boolean'
  const isDate = property.type === 'string' && property.format === 'date'
  const isLargeText = property.type === 'string' && property.format === 'text'
  const isObject = property.type === 'rel'

  let linkSchema = property.schema
  let linkId = item.id

  if (property.enum) {
    if (tabField.editable) {
      column.content = (
        <OldTabSelectField
          value={item[property.key]}
          options={property.enum}
          property={property}
          elementId={item.id}
          onEditCallback={obj.tabField.onEditCallback}
        />
      )
    } else {
      let val = ''
      property.enum.forEach((_enum: any) => {
        if (_enum.value === item[property.key]) {
          val = _enum.label
        }
      })
      column.content = val
    }
  } else if (property.as === 'color') {
    const color = property.key in item ? item[property.key] : 'blue-500'
    if (!!color) {
      const colorA = color.split("-");
      column.content = <OldBadge className='w-28' bgColor={colorA[0]} brightness={parseInt(colorA[1], 10)} size={BadgeSize.sm}>+++</OldBadge>
    } else {
      column.content = ''
    }

  } else if (property.as === 'status') {
    const val: number = item[property.key] ? item[property.key] : 0

    const activeStatus = NaeSStatuses.filter(
      (s) =>
        s.status === val &&
        s.type === property.key &&
        s.schema === property.schema
    )

    if (activeStatus.length > 0) {
      column.content = (
        <OldBadge
          bgColor={activeStatus[0].bgColor}
          brightness={activeStatus[0].brightness}
          size={'sm'}
          className={'inline-block'}
        >
          {activeStatus[0].text}
        </OldBadge>
      )
    } else {
      // console.log('statuses', UIConfig.statuses(), contentValue, property.key, property.schema);
    }
  } else if (isObject) {
    if (tabField.relName) {
      if (item[property.key] && item[property.key][tabField.relName]) {
        column.content = item[property.key][tabField.relName]
        linkId = item[property.key].id
        linkSchema = property.format ? property.format : ''
      }
    }
  } else if (isStringArray) {
    column.content = !!item[property.key] ? item[property.key].join(', ') : ''
  } else if (isFloat) {
    const val: number = item[property.key] ? item[property.key] : 0

    if (tabField.editable) {
      column.content = (
        <OldTabFloatField value={val} property={property} elementId={item.id} onEditCallback={obj.tabField.onEditCallback} />
      )
    } else {
      column.content = val.toFixed(2)
    }
  } else if (isNumber) {
    const val: number = item[property.key] ? item[property.key] : 0
    if (tabField.editable) {
      column.content = (
        <OldTabFloatField value={val} property={property} elementId={item.id} onEditCallback={obj.tabField.onEditCallback} />
      )
    } else {
      column.content = val.toFixed(0)
    }
  } else if (isDate) {
    column.props.className += ' tw3-whitespace-nowrap'
    column.content = !!item[property.key]
      ? moment(item[property.key]).format('YYYY-MM-DD')
      : ''
  } else if (isBoolean) {
    column.content = !!item[property.key] ? (
      <i className='fad fa-check'></i>
    ) : (
      <i className='fad fa-ban'></i>
    )
  } else if (isLargeText) {
    if (tabField.editable) {
      column.content = (
        <OldTabTextareaField value={item[property.key] ? item[property.key] : ''} property={property} elementId={item.id} onEditCallback={obj.tabField.onEditCallback} />
      )
    } else {
      column.content = nl2p(
        !!item[property.key] ? item[property.key] : '',
        property.key
      )
    }
  } else {
    const val = !!item[property.key] ? item[property.key] : '';
    if (tabField.editable) {
      column.content = (
        <OldTabStringField value={val} property={property} elementId={item.id} onEditCallback={obj.tabField.onEditCallback} />
      )
    } else {
      column.content = !!item[property.key] ? item[property.key] : ''
    }
  }

  if (tabField.link) {
    column.content = (
      <RsButton
        id={linkId}
        schema={linkSchema}
        defaultClick={"modal"}
        button={{
          children: column.content,
          color: "white",
          skipPadding: true,
        }}
      />
    )
  }

  // @ts-ignore
  const c: TrowCol = { ...column }
  return c
}



export const transformThProps = (
  column: TheadCol,
  property: any,
  isLink?: boolean
): TheadCol => {
  // column.props.className += ' ' + getTextAlignForProperty(property, isLink)
  column.props.textAlignment = getTextAlignForProperty(property, isLink);

  return column
}