import React, { Fragment, useEffect, useState } from 'react'

import { useLocation } from '@newageerp/v3.templates.templates-core'
import { useTranslation } from 'react-i18next'
import { TemplatesLoader, useTemplatesLoader, toast } from '@newageerp/v3.templates.templates-core';
import { fieldVisibility, IFieldVisibility, resetFieldsToDefValues } from "../../_custom/fields/fieldVisibility";
import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import { getElementFieldsToReturn, INaeEditSettings, WidgetType } from '../utils'
import OldNeWidgets from '../old-ui/OldNeWidgets'
import { getDepenciesForField } from '../../_custom/fields/fieldDependencies';
import { useUIBuilder } from '../old-ui/builder/OldUIBuilderProvider';
import { onEditElementUpdate } from '../../_custom/fields/onEditElementUpdate';
import { subscribe, unsubscribe } from '@newageerp/v3.bundles.utils-bundle';

export interface MainEditTemplateData {
  element: any,
  updateElement: (key: string, val: any) => void,
  fieldVisibility: IFieldVisibility,
  parentElement: any,
  hasChanges: boolean,
  formType: string,
  pushHiddenFields: (fieldKey: string) => void
}

interface Props {
  schema: string
  type: string
  id: string


  onSave?: (el: any, backFunc: any) => void
  onSaveAndStay?: (el: any, backFunc: any) => void

  onBack?: (isNew: boolean, el?: any) => void

  newStateOptions?: any

  skipHiddenCheck?: boolean

  contentWrap?: any

  fieldsToReturnOnSave?: string[]

  editContainerClassName?: string,

  skipRequiredCheck?: boolean,

  requiredFields?: string[],
}

export default function MainEdit(props: Props) {
  const {getEditFieldsForSchema} = useUIBuilder();
  
  const { data: tData } = useTemplatesLoader();

  let hiddenFields: any = []
  const { t } = useTranslation()
  const location = useLocation<any>()
  const locationState = location.state

  const [saveData, saveDataParams] = OpenApi.useUSave(
    props.schema,
    props.fieldsToReturnOnSave ? props.fieldsToReturnOnSave : ['id']
  )

  // WORKFLOW
  const [hasChanges, setHasChanges] = useState(false)
  const [element, setElement] = useState<any>(null)

  const updateElement = (key: string, val: any) => {
    let _el = JSON.parse(JSON.stringify(element))
    if (val !== _el[key]) {
      setHasChanges(true)
      _el[key] = val

      _el = onEditElementUpdate(props.schema, key, val, _el)

      setElement(_el)
    }
  }

  useEffect(() => {
        const listener = (e: any) => {
            updateElement(e.detail.key, e.detail.val);
        }
        subscribe(`${props.schema}-${props.type}-Update`, listener);

        return () => {
            unsubscribe(`${props.schema}-${props.type}-Update`, listener);
        }
  }, [updateElement, props.schema, props.type]);

  const updateElementBatch = (updates: any) => {
    let _el = JSON.parse(JSON.stringify(element))
    let _hasChanges = false
    Object.keys(updates).forEach((k: string) => {
      if (updates[k] !== _el[k]) {
        _hasChanges = true
        _el[k] = updates[k]

        _el = onEditElementUpdate(props.schema, k, updates[k], _el)
      }
    })

    if (_hasChanges) {
      setHasChanges(true)
      setElement(_el)
    }
  }

  const doSave = () => {
    if (saveDataParams.loading) {
      return
    }
    const isNew = props.id === 'new'
    setHasChanges(false)
    saveData({ ...element, skipRequiredCheck: props.skipRequiredCheck, requiredFields: props.requiredFields }, props.id).then((res: any) => {
      if (res) {
        if (res.error) {
        } else {
          const backFunction = () => {
            if (props.onBack) {
              props.onBack(isNew, res.data.element)
            }
          }
          if (props.onSave) {
            props.onSave(res.data.element, backFunction)
          } else {
            backFunction()
          }
        }
      }
    })
  }

  const doSaveAndStay = () => {
    if (saveDataParams.loading) {
      return
    }
    const isNew = props.id === 'new'
    setHasChanges(false)
    saveData({ ...element, skipRequiredCheck: props.skipRequiredCheck, requiredFields: props.requiredFields }, props.id).then((res: any) => {
      if (res) {
        if (res.error) {
          // @ts-ignore
          toast.error(t("Error"));
        } else {
          // @ts-ignore
          toast.success(t("Saved"));

          const backFunction = () => {
            if (props.onBack) {
              props.onBack(isNew, res.data.element)
            }
          }
          if (props.onSaveAndStay) {
            props.onSaveAndStay(res.data.element, backFunction)
          } else {
            backFunction()
          }
        }
      }
    })
  }

  const editSettings = getEditFieldsForSchema(props.schema, props.type)
  const editFields: INaeEditSettings = editSettings
    ? editSettings
    : { fields: [], schema: '', type: '' }
  let fieldsToReturn: string[] = getElementFieldsToReturn(
    editFields,
    (key: string) => getDepenciesForField(props.schema, key)
  )

  const [schemaGetData, schemaGetDataParams] = OpenApi.useUList(
    props.schema,
    fieldsToReturn
  )
  const getData = () => {
    if (props.id === 'new') {
      schemaGetData(
        { empty: true },
        1,
        1,
        undefined,
        undefined,
        props.newStateOptions ? props.newStateOptions : locationState
      )
    } else {
      schemaGetData([{ and: [['i.id', 'eq', props.id, true]] }], 1, 1)
    }
  }
  useEffect(getData, [])

  useEffect(() => {
    if (schemaGetDataParams.data.records === 1) {
      setElement(schemaGetDataParams.data.data[0])
    }
  }, [schemaGetDataParams.data.data])


  const goBack = props.onBack ? () => {
    if (props.onBack) {
      props.onBack(false);
    }
  } : undefined;

  const templateData = {
    element,
    updateElement,
    fieldVisibility,
    hasChanges,
    formType: props.type,
    pushHiddenFields: (fieldKey: string) => hiddenFields.push(fieldKey),
    onSave: doSave,
    onExtraSave: props.onSaveAndStay ? doSaveAndStay : undefined,
    onBack: goBack
  }


  resetFieldsToDefValues(
    props.schema,
    element,
    JSON.parse(JSON.stringify(hiddenFields)),
    updateElementBatch
  )


  return (
    <Fragment>
      {element ? (
        <div className={`tw3-space-y-4 tw3-max-w-[1200px] tw3-mx-auto ${props.editContainerClassName ? props.editContainerClassName : ''}`}>



          {element ? (
            <Fragment>
              <TemplatesLoader
                templates={[tData.formContent]}
                templateData={
                  {
                    ...templateData,
                    formDataError: saveDataParams.error
                  }
                }
              />

            </Fragment>
          ) : (
            <Fragment />
          )}

          <OldNeWidgets
            type={WidgetType.editRight}
            element={element}
            schema={props.schema ? props.schema : '-'}
            saveError={saveDataParams.error}
          />
        </div>
      ) : (
        <Fragment />
      )}
    </Fragment>

  )
}
