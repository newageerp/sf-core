import React, { Fragment, useEffect, useState } from 'react'
import { TemplatesLoader, useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';
import { useTranslation } from 'react-i18next';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { SFSOpenEditModalWindowProps } from '@newageerp/v3.bundles.popup-bundle';
import { useUIBuilder } from '../../old-ui/builder/OldUIBuilderProvider';
import classNames from 'classnames';
import { FieldSelect } from '@newageerp/v3.bundles.form-bundle';


interface Props {
  fieldKey: string;
  fieldSchema: string;

  relKey: string;
  relSchema: string;

  as?: string;

  fieldDependency?: string,
  fieldsExtraSelect?: string[];

  allowCreateRel?: boolean
}

export default function ObjectEditableField(props: Props) {
  const { getTabFromSchemaAndType } = useUIBuilder();

  const { t } = useTranslation();
  const [isPopup, setIsPopup] = useState(false);

  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  const updateValue = (e: any) => updateElement(props.fieldKey, e)

  const onSelect = (_id: number) => {
    getElement(
      [
        {
          "and": [
            ['i.id', '=', _id, true]
          ]
        }
      ]
    ).then((res: any) => {
      if (res.data.data.length > 0) {
        updateValue(res.data.data[0])
        setIsPopup(false)
      }
    })

  }

  const canCreate = !!props.allowCreateRel;
  const onNew = () => {
    SFSOpenEditModalWindowProps({
      options: {
        createOptions: {
          convert: {
            schema: props.fieldSchema,
            element: element,
          }
        }
      },
      id: 'new',
      schema: props.relSchema,
      onSaveCallback: (el: any, backFunc: any) => {
        backFunc();
        onSelect(el.id);
      }
    })
  }

  const [getElement] = OpenApi.useUList(props.relSchema, ['id', props.relKey, ...(props.fieldsExtraSelect ? props.fieldsExtraSelect : [])]);

  if (!element) {
    return <Fragment />;
  }


  const value = props.fieldKey ? element[props.fieldKey] : { id: 0 };


  let extraFilter = undefined
  if (props.fieldDependency) {
    const depend = props.fieldDependency.replace("?", "").split(':')
    const dependKey = depend[1].split('.')

    if (element[dependKey[0]]) {
      extraFilter = {
        and: [[depend[0], '=', element[dependKey[0]][dependKey[1]]]]
      }
    } else {
      extraFilter = { and: [[depend[0], '=', -1]] }
    }
  }

  let extraCreateStateOptions = undefined
  if (props.fieldDependency) {
    extraCreateStateOptions = {
      createOptions: {
        convert: {
          schema: props.fieldSchema,
          element: element
        }
      }
    }
  }

  const tab = getTabFromSchemaAndType(
    props.relSchema,
    'main'
  )

  const isValue = !!value && value.id > 0;

  if (props.as === 'select') {

    return (
      <SelectFieldSchema
        value={value}
        onChange={updateValue}
        schema={props.relSchema}
        tab={tab}
        fieldKey={props.relKey}
        fieldKeyExtraSelect={props.fieldsExtraSelect}
        extraFilter={extraFilter}
        className="tw3-w-full tw3-max-w-[500px] tw3-min-w-[120px]"
      />
    )
  }

  return (
    <Fragment>

      <div className={"tw3-w-full tw3-max-w-[500px] tw3-border tw3-border-slate-300 tw3-rounded tw3-flex tw3-items-center tw3-gap-2 tw3-bg-white"}>

        <button
          onClick={() => setIsPopup(true)}
          className={classNames(
            'tw3-px-[10px] tw3-py-[9px] tw3-flex  tw3-items-center tw3-duration-150  tw3-rounded-md ',
            'hover:tw3-bg-sky-100 hover:tw3-text-sky-600',
            { 'tw3-text-slate-800': isValue, 'tw3-text-slate-500': !isValue },
            'tw3-bg-white',
            'tw3-w-full',
            'tw3-text-left',
            'tw3-truncate tw3-max-h-[38px]'
          )}
          title={isValue ? value[props.relKey] : ""}
        >
          <span className={classNames([`fa-fw fa-light fa-search tw3-mr-3 tw3-text-slate-500`])} />
          <span className="tw3-text-sm">{isValue ? value[props.relKey] : t('Seach')}</span>
        </button>


        {!isValue && canCreate &&
          <ToolbarButton
            iconName='plus'
            onClick={onNew}
            bgColor={'tw3-bg-white'}
          />
        }

        {isValue &&
          <ToolbarButton
            iconName='window-close'
            textColor='tw3-text-red-600'
            onClick={() => updateValue(null)}
            bgColor={'tw3-bg-white'}
          />
        }

      </div>
      {isPopup &&

        <TemplatesLoader
          templateName="PageInlineList"
          data={{
            schema: props.relSchema,
            type: "main",
            isPopup: true,
            extraFilters: extraFilter ? [extraFilter] : undefined,
            addSelectButton: true,
            addToolbar: true,
          }}
          templateData={{
            onBack: () => {
              setIsPopup(false)
            },
            onAddSelectButton: (el: any) => {
              onSelect(el.id);
            }
          }}
        />

      }

    </Fragment>
  )
}


interface SelectFieldSchemaProps {
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

export function SelectFieldSchema(props: SelectFieldSchemaProps) {
  const { tab } = props

  const [options, setOptions] = useState<any[]>([])

  const fieldKey = props.fieldKey ? props.fieldKey : tab.fields[0].key

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
      tab.sort
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
    
      className={props.className}
      components={props.components}
      options={options}
      value={props.value ? props.value.id : 0}
      onChange={(v) => props.onChange(findElement(v))}
      icon='bars'
    />
  )
}
