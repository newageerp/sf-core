import { functions, UI } from '@newageerp/nae-react-ui';
import React, { Fragment, useState } from 'react'
import TemplateLoader, { useTemplateLoader } from '../../templates/TemplateLoader';
import { ToolbarButton } from '@newageerp/v3.buttons.toolbar-button';
import { useTranslation } from 'react-i18next';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { SFSOpenEditModalWindowProps } from '@newageerp/v3.popups.mvc-popup';


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
  const { t } = useTranslation();
  const [isPopup, setIsPopup] = useState(false);

  const { data: tData } = useTemplateLoader();
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

  const tab = functions.tabs.getTabFromSchemaAndType(
    props.relSchema,
    'main'
  )

  const isValue = !!value && value.id > 0;

  if (props.as === 'select') {

    return (
      <UI.Form.SelectFromSchema
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

      <div className={"tw3-w-full tw3-max-w-[500px] tw3-border tw3-border-slate-300 tw3-rounded tw3-flex tw3-items-center tw3-gap-2"}>

        <ToolbarButton
          iconName='search'
          onClick={() => setIsPopup(true)}
          children={isValue ? value[props.relKey] : t('Seach')}
          className={'tw3-w-full'}
        />

        {!isValue && canCreate &&
          <ToolbarButton
            iconName='plus'
            onClick={onNew}
          />
        }

        {isValue &&
          <ToolbarButton
            iconName='window-close'
            textColor='tw3-text-red-600'
            onClick={() => updateValue(null)}
          />
        }

      </div>
      {isPopup &&

        <TemplateLoader
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
